<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\DeliveryOrder;

class DashboardController extends Controller
{
    public function dashboardStats()
{
    return response()->json([
        'stats' => [
            'vehicles' => Vehicle::count(),
            'drivers' => User::role('driver')->count(),
            'activeOrders' => DeliveryOrder::where('status', 'active')->count(),
            'assignedOrders' => DeliveryOrder::where('status', 'assigned')->count(),
            'completedOrders' => DeliveryOrder::where('status', 'delivered')->count(),
        ],
        'recentOrders' => DeliveryOrder::latest()
            ->where('status', 'delivered')
            ->take(5)
            ->with('driver')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->id,
                    'driver_name' => $order->driver->name ?? 'Unassigned',
                    'completed_at' => $order->updated_at->toDateTimeString(),
                ];
            }),
    ]);
}

}
