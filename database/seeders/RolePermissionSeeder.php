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

        // Create permissions - grouped by module
        $permissions = [
            // Dashboard
            'view-dashboard',

            // User Management
            'manage-users',
            'view-users',

            // Company Management
            'manage-companies',
            'view-companies',

            // Customer Management
            'manage-customers',
            'view-customers',

            // Order Management (Import & Export)
            'manage-orders',
            'view-orders',

            // Bookkeeping / Accounting (Invoice)
            'manage-bookkeeping',
            'view-bookkeeping',

            // Vendor Management
            'manage-vendors',
            'view-vendors',

            // Operational Staff Management
            'manage-operational-staffs',
            'view-operational-staffs',

            // Reports
            'manage-reports',
            'view-reports',

            // Export & Import Data
            'export-data',
            'import-data',

            // Settings
            'manage-settings',
            'view-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ============================================
        // ROLE: OWNER (Full Access)
        // Can see all data and manage everything
        // ============================================
        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $owner->givePermissionTo(Permission::all());

        // ============================================
        // ROLE: STAFF ACCOUNTING (Akuntan)
        // Can view orders, manage bookkeeping/invoices
        // ============================================
        $accountant = Role::firstOrCreate(['name' => 'staff-accounting', 'guard_name' => 'web']);
        $accountant->givePermissionTo([
            'view-dashboard',
            'view-orders',
            'manage-bookkeeping',
            'view-bookkeeping',
            'view-reports',
            'export-data',
        ]);

        // ============================================
        // ROLE: STAFF (Regular Staff)
        // Can view data, export/import data
        // ============================================
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->givePermissionTo([
            'view-dashboard',
            'view-orders',
            'view-companies',
            'view-customers',
            'view-vendors',
            'view-operational-staffs',
            'export-data',
            'import-data',
        ]);
    }
}
