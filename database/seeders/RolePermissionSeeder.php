<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view-dashboard',
            'manage-users',
            'manage-accounting',
            'manage-reports',
            'manage-inventory',
            'manage-settings',
            'view-accounting',
            'view-reports',
            'view-inventory',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Role: Owner (full access)
        $owner = Role::create(['name' => 'owner']);
        $owner->givePermissionTo(Permission::all());

        // Role: Staff Accounting
        $staffAccounting = Role::create(['name' => 'staff-accounting']);
        $staffAccounting->givePermissionTo([
            'view-dashboard',
            'manage-accounting',
            'view-reports',
        ]);

        // Role: Staff (basic access)
        $staff = Role::create(['name' => 'staff']);
        $staff->givePermissionTo([
            'view-dashboard',
        ]);

        // Role: Manager
        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'view-dashboard',
            'view-accounting',
            'manage-reports',
            'view-reports',
            'view-inventory',
        ]);
    }
}
