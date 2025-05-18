<?php
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class VehicleController extends Controller
{
    public function index()
    {
        // if (!auth()->user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized');
        // }
        return Vehicle::with('driver')->get(); // eager load assigned driver
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'number_plate' => 'required|unique:vehicles,number_plate',
        'model' => 'required|string|max:255',
        'status' => 'in:idle,en_route,maintenance',
    ]);

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
    return User::where('role', 'driver')->get();
}
}