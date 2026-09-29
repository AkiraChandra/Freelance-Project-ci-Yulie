<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters! Follow dependencies:
     * 1. RolePermissionSeeder - creates roles and permissions
     * 2. CreateDummyUsersSeeder - creates users and assigns roles
     * 3. VendorSeeder - vendors (already exists, standalone)
     * 4. CompanySeeder - companies (depends on users)
     * 5. CustomerSeeder - customers (depends on users)
     * 6. OrderSeeder - orders, import_orders, export_orders (depends on companies, customers, users)
     * 7. OperationalStaffSeeder - operational_staffs, operational_staff_assignments (depends on orders)
     * 8. InvoiceSeeder - invoices (depends on orders)
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            CreateDummyUsersSeeder::class,
            CompanySeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
            OperationalStaffSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
