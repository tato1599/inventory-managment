<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        \Spatie\Permission\Models\Permission::create(['name' => 'manage inventory']);
        \Spatie\Permission\Models\Permission::create(['name' => 'adjust inventory']);
        \Spatie\Permission\Models\Permission::create(['name' => 'view inventory']);
        \Spatie\Permission\Models\Permission::create(['name' => 'manage users']);

        // Create roles and assign permissions
        $admin = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $admin->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        $manager = \Spatie\Permission\Models\Role::create(['name' => 'area-manager']);
        $manager->givePermissionTo(['view inventory', 'manage inventory', 'adjust inventory']);

        // Assign admin role to first user
        $user = \App\Models\User::first();
        if ($user) {
            $user->assignRole($admin);
        }
    }
}
