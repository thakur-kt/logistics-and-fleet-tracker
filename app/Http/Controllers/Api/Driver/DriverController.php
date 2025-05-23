<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Show the authenticated driver's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        // Return the authenticated user's data as JSON
        return response()->json($request->user());
    }

    /**
     * Update the authenticated driver's profile.
     * Validates input and updates user info.
     * Optionally updates driver's location in Redis (incomplete in current code).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Validate the incoming profile update data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'vehicle_number' => 'nullable|string|max:20',
        ]);

        // Update the user's profile with validated data
        $user->update($validated);

        // Placeholder for longitude and latitude (should be set from request)
        $longitude = '';
        $latitude = '';
        // Update driver's current location in Redis (incomplete: Redis and $driver not defined)
        // Redis::geoadd('drivers:locations', $longitude, $latitude, "driver_{$driver->id}");

        // Return a success response with updated user data
        return response()->json(['message' => 'Profile updated', 'user' => $user]);
    }
}
