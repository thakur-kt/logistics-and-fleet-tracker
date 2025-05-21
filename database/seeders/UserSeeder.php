<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        // Dispatcher
        $dispatcher = User::firstOrCreate(
            ['email' => 'dispatcher@example.com'],
            ['name' => 'Dispatcher User', 'password' => bcrypt('password')]
        );
        $dispatcher->assignRole('dispatcher');

        // Driver
        $driver = User::firstOrCreate(
            ['email' => 'driver@example.com'],
            ['name' => 'Driver User', 'password' => bcrypt('password')]
        );
        $driver->assignRole('driver');
        // Create 5 Dispatchers
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('dispatcher');
        });

        // Create 10 Drivers
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('driver');
        });
    }
}

    
    

