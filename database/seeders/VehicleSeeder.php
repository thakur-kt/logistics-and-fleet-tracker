<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Redis;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates 20 vehicles, assigns each random coordinates within India,
     * and stores their info in Redis for fast geospatial lookups.
     */
    public function run(): void
    {
        Vehicle::factory()->count(20)->create()->each(function ($vehicle) {
            // Generate random latitude and longitude within India's bounding box
            $latitude = $this->randomLatitudeInIndia();
            $longitude = $this->randomLongitudeInIndia();

            // Redis key for this vehicle's coordinates
            $key = "vehicle:{$vehicle->id}:coords";

            // Prepare vehicle data to store in Redis
            $data = [
                'lat' => $latitude,
                'lng' => $longitude,
                'updated_at' => now()->toDateTimeString(),
                // Note: These two fields seem swapped, check your logic if needed
                'driver_name' => $vehicle->number_plate, // Should this be driver name?
                'vehicle_number' => $vehicle->driver->name ?? null, // Should this be number_plate?
                'status' => $vehicle->status
            ];

            // Save in Redis
            Redis::set($key, json_encode($data));
        });
    }

    private function randomLatitudeInIndia(): float
    {
        return round(mt_rand(80_00000, 37000000) / 1_000_000, 6); // Between 8.0 to 37.0
    }

    private function randomLongitudeInIndia(): float
    {
        return round(mt_rand(6800000, 97000000) / 1_000_000, 6); // Between 68.0 to 97.0
    }
}
