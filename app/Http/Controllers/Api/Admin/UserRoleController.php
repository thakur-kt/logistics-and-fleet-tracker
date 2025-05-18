<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        return User::with('roles:name')->select('id', 'name', 'email')->get();
    }

    public function allRoles()
    {
        return Role::pluck('name');
    }

    public function assignRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,name'
        ]);
        $user->syncRoles($request->roles);
        return response()->json(['message' => 'Roles updated.']);
    }
}
