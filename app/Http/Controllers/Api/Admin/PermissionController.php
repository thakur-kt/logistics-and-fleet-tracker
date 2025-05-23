<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;

/**
 * Controller for managing permissions for users in the admin API.
 * Handles listing, assigning, and revoking permissions.
 */
class PermissionController extends Controller
{
    /**
     * Display a listing of all available permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        // Fetch all permissions and return only their names
        return Permission::pluck('name');
    }

    /**
     * Get all permissions assigned to a specific user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Support\Collection
     */
    public function getUserPermissions(User $user)
    {
        // Fetch all permissions for a user and return only their names
        return $user->getAllPermissions()->pluck('name');
    }

    /**
     * Assign a permission to a user.
     * Validates the request and checks for duplicate assignments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(Request $request, User $user)
    {
        // Validate the request to ensure 'permission' exists in the permissions table
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);
        // Check if the user already has the permission
        if ($user->hasPermissionTo($request->permission)) {
            return response()->json(['message' => 'Permission already assigned'], 409);
        }
        // Assign the permission to the user
        $user->givePermissionTo($request->permission);
        return response()->json(['message' => 'Permission granted']);
    }

    /**
     * Revoke a permission from a user.
     * Validates the request before revoking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function revoke(Request $request, User $user)
    {
        // Validate the request to ensure 'permission' exists in the permissions table
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);
        // Revoke the permission from the user
        $user->revokePermissionTo($request->permission);
        return response()->json(['message' => 'Permission revoked']);
    }
}
