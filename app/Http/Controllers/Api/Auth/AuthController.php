<?php
namespace App\Http\Controllers\Api\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request) {
        $data = $request->validate([
            'name' => 'required',
             'email' => 'required|email|unique:users',
            'password' => 'required|min:6', 
            'role' => 'required|in:admin,dispatcher,driver'
        ]);

        $user = User::create([
            'name' => $data['name'], 'email' => $data['email'],
            'password' => Hash::make($data['password']), 'role' => $data['role']
        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        $user->assignRole($request->role);
        return response()->json([
            'user' => $user, 
            'roles' => $user->getRoleNames(),
            // 'permissions' => $user->getAllPermissions()->pluck('name'),
            'token' => $token
        ]);
    }

    public function login(Request $request) {
        $data = $request->validate([
            'email' => 'required|email', 'password' => 'required'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user, 
            'roles' => $user->getRoleNames(),
            // 'permissions' => $user->getAllPermissions()->pluck('name'),
            'token' => $token]);
    }

    public function logout(Request $request) {
    //    dd($request->user());
       // Safely delete the current token
    // $request->user()?->currentAccessToken()?->delete();

    return response()->json(['message' => 'Logged out successfully']);
    }
}
