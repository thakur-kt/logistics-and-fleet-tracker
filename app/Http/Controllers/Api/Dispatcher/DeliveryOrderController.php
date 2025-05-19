<?php
namespace App\Http\Controllers\Api\Dispatcher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\DeliveryOrder;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\DeliveryOrderUpdated;
class DeliveryOrderController extends Controller
{
    public function index(Request $request)
    {
        return DeliveryOrder::with(['vehicle.driver', 'user'])
        ->when(!$request->user()->hasRole('admin'),function($q){
            $q->where('user_id',auth()->id());
        })->latest()->get();
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'vehicle_id' => 'required',
        'pickup_location' => 'required|string|max:255',
        'dropoff_location' => 'required|string|max:255',
        'scheduled_at' => 'nullable|date|after:now',
    ]);

    $order = DeliveryOrder::create($validated);

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
        'user_id'=>'required',
         'pickup_location' => 'required|string|max:255',
        'dropoff_location' => 'required|string|max:255',
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