<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Permissions
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

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $dispatcher = Role::firstOrCreate(['name' => 'dispatcher']);
        $driver = Role::firstOrCreate(['name' => 'driver']);

        // Assign Permissions to Roles
        $admin->givePermissionTo(Permission::all());

        $dispatcher->givePermissionTo([
            'assign deliveries',
            'track vehicles',
            'send chat',
            'receive chat',
        ]);

        $driver->givePermissionTo([
            'track vehicles',
            'receive chat',
            'send chat',
        ]);
    }
}
