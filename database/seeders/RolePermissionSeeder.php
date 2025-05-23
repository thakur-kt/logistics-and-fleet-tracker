<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear cached permissions to ensure fresh seeding
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions used in the system
        $permissions = [
            'manage users',
            'manage vehicles',
            'assign deliveries',
            'track vehicles',
            'send chat',
            'receive chat',
            'view assigned orders',
            'update order status',
            'view profile',
            'update location'
        ];

        // Create each permission if it doesn't already exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles for the system
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $dispatcher = Role::firstOrCreate(['name' => 'dispatcher']);
        $driver = Role::firstOrCreate(['name' => 'driver']);

        // Assign all permissions to the admin role
        $admin->givePermissionTo(Permission::all());

        // Assign a subset of permissions to the dispatcher role
        $dispatcher->givePermissionTo([
            'assign deliveries',
            'track vehicles',
            'send chat',
            'receive chat',
        ]);

        // Assign a subset of permissions to the driver role
        $driver->givePermissionTo([
            'track vehicles',
            'receive chat',
            'send chat',
        ]);
    }
}
