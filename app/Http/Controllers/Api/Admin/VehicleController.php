<?php
namespace App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Coordinate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;
class VehicleController extends Controller
{
    public function index(Request $request)
    {
        // if (!auth()->user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized');
        // }
        $query = Vehicle::query();

        if ($request->search) {
            $query->where('number_plate', 'like', "%{$request->search}%");
        }
    
        if ($request->status) {
            $query->where('status', $request->status);
        }
    
        if ($request->sort_by && $request->sort_dir) {
            $query->orderBy($request->sort_by, $request->sort_dir);
        }
        return  $query->paginate(10); // eager load assigned driver
        
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        // 'user_id' => 'required|exists:users,id',
        'number_plate' => 'required|unique:vehicles,number_plate',
        'model' => 'required|string|max:255',
        'status' => 'in:idle,en_route,maintenance',
    ]);
    $validated['user_id'] = $request->user()->id;
    $vehicle = Vehicle::create($validated);

    return response()->json(['vehicle' => $vehicle], 201);
}
public function show($id)
{
    $vehicle = Vehicle::with('driver')->findOrFail($id);
    return response()->json($vehicle);
}

public function update(Request $request, $id)
{
    $vehicle = Vehicle::findOrFail($id);

    $validated = $request->validate([
        'user_id' => 'nullable|exists:users,id',
        'number_plate' => 'sometimes|required|unique:vehicles,number_plate,' . $vehicle->id,
        'model' => 'sometimes|required|string|max:255',
        'status' => 'in:idle,en_route,maintenance',
    ]);

    $vehicle->update($validated);

    return response()->json(['vehicle' => $vehicle]);
}
public function destroy($id)
{
    $vehicle = Vehicle::findOrFail($id);
    $vehicle->delete();

    return response()->json(['message' => 'Vehicle deleted']);
}
public function drivers()
{
    // Assuming 'driver' role is flagged via a 'role' field or via spatie/laravel-permission
    return User::role('driver')->get();
}

public function updateLocation(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|integer|exists:vehicles,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $vehicleId = $request->vehicle_id;
        $key = "vehicle:{$vehicleId}:coords";
        $vehicle = Vehicle::findOrFail($vehicleId);
        $data = [
            'lat' => $request->latitude,
            'lng' => $request->longitude,
            'updated_at' => Carbon::now()->toDateTimeString(),
            'driver_name'=>$vehicle->number_plate,
            'vehicle_number'=>$vehicle->driver->name,
            'status'=>$vehicle->status
        ];
        $coords= new Coordinate();
            $coords->latitude =  $request->latitude;
            $coords->longitude =  $request->longitude;
            $coords->vehicle_id =  $request->vehicle_id;
            $coords->save();
        
            // event(new \App\Events\VehicleLocationUpdated($coords));
        
        Redis::set($key, json_encode($data));

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => $data
        ]);
    }
    public function getLocation($vehicleId)
{
    $key = "vehicle:{$vehicleId}:coords";

    $coords = Redis::get($key);

    if (!$coords) {
        return response()->json(['message' => 'No coordinates found'], 404);
    }

    return response()->json(json_decode($coords, true));
}
public function getAllVehicleCoords()
{
    $keys = Redis::keys('vehicle:*:coords');
    $vehicles = [];

    foreach ($keys as $key) {
        $vehicleId = explode(':', $key)[1];
        $coords = json_decode(Redis::get($key), true);
        if ($coords) {
            $vehicles[] = [
                'id' => $vehicleId,
                'lat' => $coords['lat'],
                'lng' => $coords['lng'],
                'driver_name'=>$coords['driver_name'],
                'vehicle_number'=>$coords['vehicle_number'],
                'status'=>$coords['status']
            ];
        }
    }

    return response()->json($vehicles);
}
}