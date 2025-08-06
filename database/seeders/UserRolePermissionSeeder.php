<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions with 'admin' guard
        $permissions = [
            'view-role', 'create-role', 'update-role', 'delete-role',
            'view-permission', 'create-permission', 'update-permission', 'delete-permission',
            'view-user', 'create-user', 'update-user', 'delete-user',
            'view-product', 'create-product', 'update-product', 'delete-product',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Create Roles with 'admin' guard
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);

        // Assign all permissions to the 'super-admin' role
        $superAdminRole->givePermissionTo(Permission::all());

        // Assign specific permissions to the 'admin' role
        $adminPermissions = [
            'create-role', 'view-role', 'update-role',
            'create-permission', 'view-permission',
            'create-user', 'view-user', 'update-user',
            'create-product', 'view-product', 'update-product',
        ];
        $adminRole->givePermissionTo($adminPermissions);

        // Assign Roles to Users
        $superAdminUser = Admin::where('email','spider@gmail.com')->first();
        if ($superAdminUser) {
            $superAdminUser->assignRole($superAdminRole);
        } else {
            $this->command->warn('Super Admin user (ID: 1) not found.');
        }

        $adminUser = Admin::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('12345678'),
            'status' => 'unactive',
        ]);

        $adminUser->assignRole($adminRole);
    }
}
