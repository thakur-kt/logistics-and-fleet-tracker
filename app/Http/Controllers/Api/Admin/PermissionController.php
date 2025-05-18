<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return Permission::pluck('name');
    }

    public function getUserPermissions(User $user)
    {
        return $user->getAllPermissions()->pluck('name');
    }

    public function assign(Request $request, User $user)
    {
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $user->givePermissionTo($request->permission);
        return response()->json(['message' => 'Permission granted']);
    }

    public function revoke(Request $request, User $user)
    {
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $user->revokePermissionTo($request->permission);
        return response()->json(['message' => 'Permission revoked']);
    }
}
