<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

/**
 * Controller for managing user roles in the admin API.
 * Handles listing users with roles, listing all roles, and assigning roles to users.
 */
class UserRoleController extends Controller
{
    /**
     * Get all users with their assigned roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        // Return users with their roles (only role names), selecting basic user info
        return User::with('roles:name')->select('id', 'name', 'email')->get();
    }

    /**
     * Get a list of all available roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allRoles()
    {
        // Return all role names
        return Role::pluck('name');
    }

    /**
     * Assign roles to a user.
     * Validates that each role exists before assignment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignRoles(Request $request, User $user)
    {
        // Validate that 'roles' is an array and each role exists in the roles table
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,name'
        ]);
        // Sync the user's roles with the provided roles (removes old, adds new)
        $user->syncRoles($request->roles);
        return response()->json(['message' => 'Roles updated.']);
    }
}
