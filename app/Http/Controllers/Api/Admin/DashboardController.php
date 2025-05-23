<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\DeliveryOrder;

/**
 * Controller for handling dashboard statistics for the admin panel.
 */
class DashboardController extends Controller
{
    /**
     * Get dashboard statistics and recent completed orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardStats()
    {
        // Return a JSON response containing statistics and recent completed orders
        return response()->json([
            'stats' => [
                // Total number of vehicles in the system
                'vehicles' => Vehicle::count(),
                // Total number of users with the 'driver' role
                'drivers' => User::role('driver')->count(),
                // Count of active delivery orders
                'activeOrders' => DeliveryOrder::where('status', 'active')->count(),
                // Count of assigned delivery orders
                'assignedOrders' => DeliveryOrder::where('status', 'assigned')->count(),
                // Count of completed (delivered) orders
                'completedOrders' => DeliveryOrder::where('status', 'delivered')->count(),
            ],
            // Fetch the 5 most recent delivered orders with driver info
            'recentOrders' => DeliveryOrder::latest()
                ->where('status', 'delivered')
                ->take(5)
                ->with('driver')
                ->get()
                ->map(function ($order) {
                    // Map each order to a simplified array structure
                    return [
                        'id' => $order->id,
                        'order_id' => $order->id,
                        // Show driver's name or 'Unassigned' if not available
                        'driver_name' => $order->driver->name ?? 'Unassigned',
                        // Show the completion timestamp
                        'completed_at' => $order->updated_at->toDateTimeString(),
                    ];
                }),
        ]);
    }
}
