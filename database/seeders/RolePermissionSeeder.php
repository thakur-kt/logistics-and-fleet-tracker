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

        // Define all permissions used in the system, grouped by feature
        $permissions = [
            'Vehicle Management' => [
                'view vehicles',
                'add vehicle',
                'edit vehicle',
                'delete vehicle',
            ],
            'Driver Management' => [
                'view drivers',
                'assign drivers',
                'track drivers',
            ],
            'Orders' => [
                'create order',
                'assign order',
                'update order status',
            ]
        ];

        // Create each permission if it doesn't already exist
        foreach ($permissions as $group => $perms) {
            foreach ($perms as $perm) {
                Permission::firstOrCreate(['name' => $perm]);
            }
        }

        // Define roles for the system and assign permissions

        // Admin gets all permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Dispatcher gets a subset of permissions
        $dispatcher = Role::firstOrCreate(['name' => 'dispatcher']);
        $dispatcher->givePermissionTo([
            'view vehicles', 
            'assign order', 
        ]);

        // Driver gets a different subset of permissions
        $driver = Role::firstOrCreate(['name' => 'driver']);
        $driver->givePermissionTo([
            'update order status', 
        ]);
    }
}
