<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $dispatcher = Role::create(['name' => 'dispatcher']);
        $driver = Role::create(['name' => 'driver']);
    
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'assign deliveries']);
        Permission::create(['name' => 'track vehicle']);
    
        $admin->givePermissionTo('manage users');
        $dispatcher->givePermissionTo(['assign deliveries', 'track vehicle']);
        $driver->givePermissionTo('track vehicle');


        // Assign permissions
$driverRole = Role::create(['name' => 'driver']);
Permission::create(['name' => 'view assigned orders']);
Permission::create(['name' => 'update order status']);
Permission::create(['name' => 'view profile']);
Permission::create(['name' => 'update location']);
$driverRole->givePermissionTo([
    'view assigned orders',
    'update order status',
    'view profile',
    'update location',
]);
    }
}
