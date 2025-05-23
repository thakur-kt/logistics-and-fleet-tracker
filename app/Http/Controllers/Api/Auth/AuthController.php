<?php
namespace App\Http\Controllers\Api\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Register a new user and assign a role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        // Validate incoming registration data
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6', 
            'role' => 'required|in:admin,dispatcher,driver'
        ]);

        // Create the user with hashed password and role
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role']
        ]);

        // Generate an API token for the user
        $token = $user->createToken('api-token')->plainTextToken;

        // Assign the specified role to the user
        $user->assignRole($request->role);

        // Return user info, roles, permissions, and token
        return response()->json([
            'user' => $user, 
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'token' => $token
        ]);
    }

    /**
     * Authenticate a user and return a token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        // Validate login credentials
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Find user by email
        $user = User::where('email', $data['email'])->first();

        // Check if user exists and password matches
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate an API token for the user
        $token = $user->createToken('api-token')->plainTextToken;

        // Return user info, roles, permissions, and token
        return response()->json([
            'user' => [
                'id'=> $user->id,
                'name'=> $user->name,
                'role'=> $user->role
            ], 
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'token' => $token
        ]);
    }

    /**
     * Logout the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {
        // delete the current access token to log out the user
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
