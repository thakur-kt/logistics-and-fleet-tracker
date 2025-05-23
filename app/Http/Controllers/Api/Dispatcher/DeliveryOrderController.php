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

/**
 * Controller for managing delivery orders for dispatchers.
 * Handles listing, creating, viewing, updating, and deleting delivery orders.
 */
class DeliveryOrderController extends Controller
{
    /**
     * List all delivery orders.
     * If the user is not an admin, only show orders assigned to the current driver.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        // Fetch delivery orders with related vehicle, user, and driver info
        // If not admin, filter orders by current user's driver_id
        return DeliveryOrder::with(['vehicle', 'user', 'driver'])
            ->when(
                !$request->user()->hasRole('admin'),
                function ($q) {
                    $q->where('driver_id', auth()->id());
                }
            )
            ->latest()
            ->get();
    }

    /**
     * Store a new delivery order and dispatch auto-assignment job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate incoming order data
        $validated = $request->validate([
            'vehicle_id' => 'required',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        // Set the user_id to the currently authenticated user
        $validated['user_id'] = auth()->id();

        // Create the delivery order
        $order = DeliveryOrder::create($validated);

        // Dispatch auto-assignment job for the order
        AutoAssignDelivery::dispatch($order);

        // Return the created order with 201 status
        return response()->json(['order' => $order], 201);
    }

    /**
     * Show a specific delivery order with related vehicle, driver, and user info.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the order or fail, eager load vehicle's driver and user
        $order = DeliveryOrder::with(['vehicle.driver', 'user'])->findOrFail($id);
        return response()->json($order);
    }

    /**
     * Update a delivery order and fire an update event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find the order or fail
        $order = DeliveryOrder::findOrFail($id);

        // Validate update data
        $validated = $request->validate([
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'status' => 'nullable|in:pending,assigned,delivered,cancelled',
            // 'scheduled_at' => 'nullable|date|after:now',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'driver_id' => 'required'
        ]);

        // If vehicle_id is set, mark status as assigned
        if (isset($validated['vehicle_id'])) {
            $validated['status'] = 'assigned';
        }

        // Update the order with validated data
        $order->update($validated);

        // Fire the DeliveryOrderUpdated event
        // event(new DeliveryOrderUpdated($order));

        // Return the updated order
        return response()->json(['order' => $order]);
    }

    /**
     * Delete a delivery order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the order or fail
        $order = DeliveryOrder::findOrFail($id);

        // Delete the order
        $order->delete();

        // Return success message
        return response()->json(['message' => 'Order deleted successfully']);
    }
}