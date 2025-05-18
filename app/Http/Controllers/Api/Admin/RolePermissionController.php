<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
   
    public function roles()
    {
        return Role::with('permissions:name')->get();
    }

    public function permissions()
    {
        return Permission::pluck('name');
    }

    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        return Role::create(['name' => $request->name]);
    }

    public function createPermission(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);
        return Permission::create(['name' => $request->name]);
    }

    public function assignPermissionToRole(Request $request)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'permission' => 'required|exists:permissions,name',
        ]);
        $role = Role::findByName($request->role);
        $role->givePermissionTo($request->permission);
        return response()->json(['message' => 'Permission assigned to role']);
    }

    public function removePermissionFromRole(Request $request)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'permission' => 'required|exists:permissions,name',
        ]);
        $role = Role::findByName($request->role);
        $role->revokePermissionTo($request->permission);
        return response()->json(['message' => 'Permission removed from role']);
    }
}
