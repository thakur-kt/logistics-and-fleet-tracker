<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database with initial users and assign roles.
     *
     * @return void
     */
    public function run()
    {
        // Create or get the admin user and assign the 'admin' role
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        // Create or get the dispatcher user and assign the 'dispatcher' role
        $dispatcher = User::firstOrCreate(
            ['email' => 'dispatcher@example.com'],
            ['name' => 'Dispatcher User', 'password' => bcrypt('password')]
        );
        $dispatcher->assignRole('dispatcher');

        // Create or get the driver user and assign the 'driver' role
        $driver = User::firstOrCreate(
            ['email' => 'driver@example.com'],
            ['name' => 'Driver User', 'password' => bcrypt('password')]
        );
        $driver->assignRole('driver');

        // Create 5 additional dispatcher users using the factory and assign 'dispatcher' role
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('dispatcher');
        });

        // Create 10 additional driver users using the factory and assign 'driver' role
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('driver');
        });
    }
}




