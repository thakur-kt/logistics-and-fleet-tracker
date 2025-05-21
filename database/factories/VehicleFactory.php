<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class VehicleFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::role('driver')->inRandomOrder()->first()?->id,
            'number_plate' => strtoupper($this->faker->bothify('??## ???')),
            'model' => $this->faker->word . ' ' . $this->faker->randomDigit(),
            'status' => $this->faker->randomElement(['idle', 'en_route', 'maintenance']),
            'last_lat' => $this->faker->latitude(28.4, 28.8),  // Random near Delhi
            'last_lng' => $this->faker->longitude(76.9, 77.4),
        ];
    }

  
}
