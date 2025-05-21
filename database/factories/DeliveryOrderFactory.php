<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\DeliveryOrder;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class DeliveryOrderFactory extends Factory
{
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::role('admin')->inRandomOrder()->first()?->id,
            'driver_id' => User::role('driver')->inRandomOrder()->first()?->id,
            'vehicle_id' => Vehicle::inRandomOrder()->first()?->id,
            'pickup_location' => $this->faker->streetAddress . ', ' . $this->faker->city,
            'dropoff_location' => $this->faker->streetAddress . ', ' . $this->faker->city,
            'scheduled_at' => now()->addDays(rand(0, 5)),
            'status' => $this->faker->randomElement(['pending', 'assigned', 'delivered', 'cancelled']),
        ];
    }

  
}
