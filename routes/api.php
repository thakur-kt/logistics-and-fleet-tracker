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

Broadcast::routes(['middleware' => ['auth:sanctum']]);
// Public
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
        'roles' => $request->user()->getRoleNames(),
        'permissions' => $request->user()->getAllPermissions()->pluck('name'),
    ]);
});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

//roles
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserRoleController::class, 'index']);
    Route::get('/roles/all', [UserRoleController::class, 'allRoles']);
    Route::post('/users/{user}/roles', [UserRoleController::class, 'assignRoles']);
});

//permissions
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('/admin')->group(function () {
    // Route::get('/permissions', [PermissionController::class, 'index']);
    Route::get('/users/{user}/permissions', [PermissionController::class, 'getUserPermissions']);
    Route::post('/users/{user}/assign-permission', [PermissionController::class, 'assign']);
    Route::post('/users/{user}/revoke-permission', [PermissionController::class, 'revoke']);
    Route::get('/roles', [RolePermissionController::class, 'roles']);
    Route::get('/permissions', [RolePermissionController::class, 'permissions']);
    Route::post('/roles', [RolePermissionController::class, 'createRole']);
    Route::post('/permissions', [RolePermissionController::class, 'createPermission']);
    Route::post('/roles/assign-permission', [RolePermissionController::class, 'assignPermissionToRole']);
    Route::post('/roles/remove-permission', [RolePermissionController::class, 'removePermissionFromRole']);
});

//roles and permission
Route::middleware(['auth:sanctum'])->prefix('/admin')->group(function () {
   
    Route::get('/dashboard-stats', [DashboardController::class, 'dashboardStats']);

   
});
// Protected
Route::middleware('auth:sanctum')->group(function () {
    
    Route::apiResource('vehicles', VehicleController::class);
    Route::get('drivers', [VehicleController::class, 'drivers']);
    Route::apiResource('delivery-orders', DeliveryOrderController::class);
    Route::post('/vehicles/{id}/location', [VehicleController::class, 'updateLocation']);
   
    Route::get('drivers/me', [DriverController::class, 'show']);
    Route::put('drivers/me', [DriverController::class, 'update']);

    Route::post('/tracking/update', [DriverController::class, 'updateLocation']);
    // Route::get('/tracking/{vehicleId}', [VehicleController::class, 'getLocation']);
    Route::get('/vehicles-coordinates', [VehicleController::class, 'getAllVehicleCoords']);

});