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
     */
    public function run(): void
    {
        Vehicle::factory()->count(20)->create()->each(function ($vehicle) {
            // Random coordinates within India (approximate bounding box)
            $latitude = $this->randomLatitudeInIndia();
            $longitude = $this->randomLongitudeInIndia();
            $key = "vehicle:{$vehicle->id}:coords";
            $data = [
                'lat' => $latitude,
                'lng' =>$longitude,
                'updated_at' => now()->toDateTimeString(),
                'driver_name'=>$vehicle->number_plate,
                'vehicle_number'=>$vehicle->driver->name,
                'status'=>$vehicle->status
            ];
            // Save in Redis (e.g., vehicles:{id})
          
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
