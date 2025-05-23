<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Admin\UserRoleController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\RolePermissionController;
use App\Http\Controllers\Api\Admin\VehicleController;
use App\Http\Controllers\Api\Dispatcher\DeliveryOrderController;
use App\Http\Controllers\Api\Driver\DriverController;
use App\Http\Controllers\Api\Admin\DashboardController;
// use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Broadcast;

// Register broadcasting routes with Sanctum authentication
Broadcast::routes(['middleware' => ['auth:sanctum']]);

// Public authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Get authenticated user info, roles, and permissions
Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
        'roles' => $request->user()->getRoleNames(),
        'permissions' => $request->user()->getAllPermissions()->pluck('name'),
    ]);
});

// Logout route for authenticated users
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);



// Admin-only routes for managing permissions and roles
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('/admin')->group(function () {
   Route::get('/users', [UserRoleController::class, 'index']); // List users with roles
    Route::get('/roles/all', [UserRoleController::class, 'allRoles']); // List all roles
    Route::post('/users/{user}/roles', [UserRoleController::class, 'assignRoles']); // Assign roles to user
    Route::get('/users/{user}/permissions', [PermissionController::class, 'getUserPermissions']); // Get user permissions
    Route::post('/users/{user}/assign-permission', [PermissionController::class, 'assign']); // Assign permission to user
    Route::post('/users/{user}/revoke-permission', [PermissionController::class, 'revoke']); // Revoke permission from user
    Route::get('/roles', [RolePermissionController::class, 'roles']); // List roles with permissions
    Route::get('/permissions', [RolePermissionController::class, 'permissions']); // List all permissions
    Route::post('/roles', [RolePermissionController::class, 'createRole']); // Create new role
    Route::post('/permissions', [RolePermissionController::class, 'createPermission']); // Create new permission
    Route::post('/roles/assign-permission', [RolePermissionController::class, 'assignPermissionToRole']); // Assign permission to role
    Route::post('/roles/remove-permission', [RolePermissionController::class, 'removePermissionFromRole']); // Remove permission from role
});

// Admin dashboard stats route (requires authentication)
Route::middleware(['auth:sanctum'])->prefix('/admin')->group(function () {
    Route::get('/dashboard-stats', [DashboardController::class, 'dashboardStats']);
});

// Protected routes for authenticated users (drivers, dispatchers, etc.)
Route::middleware('auth:sanctum')->group(function () {
    // Vehicle resource routes (CRUD)
    Route::apiResource('vehicles', VehicleController::class);
    // Get all drivers
    Route::get('drivers', [VehicleController::class, 'drivers']);
    // Delivery order resource routes (CRUD)
    Route::apiResource('delivery-orders', DeliveryOrderController::class);
    // Update vehicle location
    Route::post('/vehicles/{id}/location', [VehicleController::class, 'updateLocation']);
    // Driver profile routes
    Route::get('drivers/me', [DriverController::class, 'show']);
    Route::put('drivers/me', [DriverController::class, 'update']);
    // Update driver tracking/location
    Route::post('/tracking/update', [DriverController::class, 'updateLocation']);
    // Route::get('/tracking/{vehicleId}', [VehicleController::class, 'getLocation']); // (commented out)
    // Get all vehicle coordinates
    Route::get('/vehicles-coordinates', [VehicleController::class, 'getAllVehicleCoords']);
});