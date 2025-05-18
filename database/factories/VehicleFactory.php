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
            'user_id' => User::factory(),
    'number_plate' => strtoupper(fake()->bothify('??##???')),
    'model' => fake()->randomElement(['Tata Ace', 'Mahindra Bolero', 'Ashok Leyland']),
    'status' => fake()->randomElement(['idle', 'en_route', 'maintenance']),
        ];
    }

  
}
