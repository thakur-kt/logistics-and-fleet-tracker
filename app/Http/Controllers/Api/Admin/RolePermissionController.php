<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Controller for managing roles and permissions in the admin API.
 * Handles listing, creating, assigning, and removing permissions for roles.
 */
class RolePermissionController extends Controller
{
    /**
     * Get all roles with their associated permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function roles()
    {
        // Return all roles with their permissions (only permission names)
        return Role::with('permissions:name')->get();
    }

    /**
     * Get a list of all available permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function permissions()
    {
        // Return all permission names
        return Permission::pluck('name');
    }

    /**
     * Create a new role.
     * Validates that the role name is unique.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Spatie\Permission\Models\Role
     */
    public function createRole(Request $request)
    {
        // Validate the request to ensure 'name' is provided and unique
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        // Create and return the new role
        return Role::create(['name' => $request->name]);
    }

    /**
     * Create a new permission.
     * Validates that the permission name is unique.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Spatie\Permission\Models\Permission
     */
    public function createPermission(Request $request)
    {
        // Validate the request to ensure 'name' is provided and unique
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);
        // Create and return the new permission
        return Permission::create(['name' => $request->name]);
    }

    /**
     * Assign a permission to a role.
     * Validates that both role and permission exist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignPermissionToRole(Request $request)
    {
        // Validate the request to ensure both role and permission exist
        $request->validate([
            'role' => 'required|exists:roles,name',
            'permission' => 'required|exists:permissions,name',
        ]);
        // Find the role by name and assign the permission
        $role = Role::findByName($request->role);
        $role->givePermissionTo($request->permission);
        return response()->json(['message' => 'Permission assigned to role']);
    }

    /**
     * Remove a permission from a role.
     * Validates that both role and permission exist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removePermissionFromRole(Request $request)
    {
        // Validate the request to ensure both role and permission exist
        $request->validate([
            'role' => 'required|exists:roles,name',
            'permission' => 'required|exists:permissions,name',
        ]);
        // Find the role by name and revoke the permission
        $role = Role::findByName($request->role);
        $role->revokePermissionTo($request->permission);
        return response()->json(['message' => 'Permission removed from role']);
    }
}
