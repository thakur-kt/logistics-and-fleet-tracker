<?php
namespace App\Http\Controllers\Api\Dispatcher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\DeliveryOrder;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\DeliveryOrderUpdated;
use App\Jobs\AutoAssignDelivery;
class DeliveryOrderController extends Controller
{
    public function index(Request $request)
    {
        return DeliveryOrder::with(['vehicle', 'user','driver'])
        ->when(!$request->user()->hasRole('admin'),function($q){
            $q->where('user_id',auth()->id());
        })->latest()->get();
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'vehicle_id' => 'required',
        'pickup_location' => 'required|string|max:255',
        'dropoff_location' => 'required|string|max:255',
        'scheduled_at' => 'nullable|date|after:now',
    ]);

    $validated['user_id']= auth()->id();
    $order = DeliveryOrder::create($validated);
 // Dispatch auto-assignment job
 AutoAssignDelivery::dispatch($order);
    return response()->json(['order' => $order], 201);
}
public function show($id)
{
    $order = DeliveryOrder::with(['vehicle.driver', 'user'])->findOrFail($id);
    return response()->json($order);
}

public function update(Request $request, $id)
{
    $order = DeliveryOrder::findOrFail($id);

    $validated = $request->validate([
        'vehicle_id' => 'nullable|exists:vehicles,id',
        'status' => 'nullable|in:pending,assigned,delivered,cancelled',
        'scheduled_at' => 'nullable|date|after:now',
         'pickup_location' => 'required|string|max:255',
        'dropoff_location' => 'required|string|max:255',
        'driver_id'=>'required'
    ]);
    if (isset($validated['vehicle_id'])) {
        $validated['status'] = 'assigned';
    }

    $order->update($validated);
    // event(new DeliveryOrderUpdated($order));
    return response()->json(['order' => $order]);
}

public function destroy($id)
{
    $order = DeliveryOrder::findOrFail($id);
    $order->delete();

    return response()->json(['message' => 'Order deleted successfully']);
}

}