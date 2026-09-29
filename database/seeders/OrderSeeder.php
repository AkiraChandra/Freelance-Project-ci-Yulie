<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\ImportOrder;
use App\Models\ExportOrder;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $owner = User::where('email', 'akira.chandra@binus.ac.id')->first();
        $staff = User::where('email', 'akira.chandra@binus.edu')->first();

        // Get companies
        $company1 = Company::where('company_code', 'CMP001')->first();
        $company2 = Company::where('company_code', 'CMP002')->first();

        // Get customers
        $customer3 = Customer::where('customer_code', 'C0001')->first(); // PT Petrokimia
        $customer2 = Customer::where('customer_code', '89186')->first(); // PT Maju Bersamas
        $customer4 = Customer::where('customer_code', 'C0006')->first(); // PT Astra
        $customer5 = Customer::where('customer_code', 'C0007')->first(); // PT Salim Ivomas

        // ============================================
        // IMPORT ORDER 1 - On Going
        // ============================================
        $order1 = Order::firstOrCreate(
            ['order_code' => 'ORD-IMP-001'],
            [
                'type' => 'import',
                'order_date' => Carbon::now()->subDays(10),
                'company_id' => $company1 ? $company1->id : 1,
                'status' => 'on going',
                'notes' => 'Import material steel untuk proyek infrastruktur',
                'created_by' => $owner->id,
            ]
        );

        ImportOrder::firstOrCreate(
            ['import_order_number' => 'IMP-2026-001'],
            [
                'order_id' => $order1->id,
                'customer_id' => $customer3 ? $customer3->id : 3,
                'order_date' => Carbon::now()->subDays(10),
                'company_id' => $company1 ? $company1->id : 1,
                'bl_number' => 'BL-SEA-001234',
                'product_name' => 'Hot Rolled Steel Coil',
                'shipping_line' => 'MISC Berhad',
                'vessel_name' => 'MV Western Jakarta',
                'voy_number' => 'V-2401',
                'vessel_arrival_date' => Carbon::now()->subDays(3),
                'pib_number' => 'PIB-001-2026',
                'port' => 'Tanjung Priok, Jakarta',
                'do_date' => Carbon::now()->subDays(2),
                'detention_deadline' => Carbon::now()->addDays(7),
                'demurrage_date' => Carbon::now()->subDays(1),
                'release_date' => Carbon::now()->subDays(1),
                'container_return_date' => Carbon::now()->addDays(5),
                'issue' => null,
                'status' => 'on going',
                'created_by' => $owner->id,
            ]
        );

        // ============================================
        // IMPORT ORDER 2 - On Going
        // ============================================
        $order2 = Order::firstOrCreate(
            ['order_code' => 'ORD-IMP-002'],
            [
                'type' => 'import',
                'order_date' => Carbon::now()->subDays(7),
                'company_id' => $company2 ? $company2->id : 2,
                'status' => 'on going',
                'notes' => 'Import chemical raw material',
                'created_by' => $staff->id,
            ]
        );

        ImportOrder::firstOrCreate(
            ['import_order_number' => 'IMP-2026-002'],
            [
                'order_id' => $order2->id,
                'customer_id' => $customer2 ? $customer2->id : 2,
                'order_date' => Carbon::now()->subDays(7),
                'company_id' => $company2 ? $company2->id : 2,
                'bl_number' => 'BL-SEA-005678',
                'product_name' => 'Urea Fertilizer',
                'shipping_line' => 'Evergreen Marine',
                'vessel_name' => 'Ever Bright',
                'voy_number' => 'V-2402',
                'vessel_arrival_date' => Carbon::now()->addDays(2),
                'pib_number' => null,
                'port' => 'Semayang, Balikpapan',
                'do_date' => null,
                'detention_deadline' => null,
                'demurrage_date' => null,
                'release_date' => null,
                'container_return_date' => null,
                'issue' => 'Masih dalam perjalanan laut',
                'status' => 'on going',
                'created_by' => $staff->id,
            ]
        );

        // ============================================
        // EXPORT ORDER 1 - On Going
        // ============================================
        $order3 = Order::firstOrCreate(
            ['order_code' => 'ORD-EXP-001'],
            [
                'type' => 'export',
                'order_date' => Carbon::now()->subDays(5),
                'company_id' => $company1 ? $company1->id : 1,
                'status' => 'on going',
                'notes' => 'Export machinery parts ke Singapore',
                'created_by' => $staff->id,
            ]
        );

        ExportOrder::firstOrCreate(
            ['export_order_number' => 'EXP-2026-001'],
            [
                'order_id' => $order3->id,
                'customer_id' => $customer4 ? $customer4->id : 6,
                'order_date' => Carbon::now()->subDays(5),
                'company_id' => $company1 ? $company1->id : 1,
                'shipping_number' => 'SHP-2026-001',
                'do_number' => 'DO-EXP-001',
                'product_name' => 'Machinery Spare Parts',
                'shipping_line' => 'Nippon Yusen Kaisha (NYK)',
                'vessel_name' => 'NYK Olympus',
                'voy_number' => 'V-2403',
                'closing_date' => Carbon::now()->addDays(3),
                'peb_number' => 'PEB-001-2026',
                'depo' => 'SPOIL CONTAINER DEPOT',
                'detention_deadline' => Carbon::now()->addDays(5),
                'pickup_date' => Carbon::now()->subDays(2),
                'return_date' => Carbon::now()->addDays(2),
                'issue' => null,
                'status' => 'on going',
                'created_by' => $staff->id,
            ]
        );

        // ============================================
        // EXPORT ORDER 2 - Completed
        // ============================================
        $order4 = Order::firstOrCreate(
            ['order_code' => 'ORD-EXP-002'],
            [
                'type' => 'export',
                'order_date' => Carbon::now()->subDays(20),
                'company_id' => $company1 ? $company1->id : 1,
                'status' => 'completed',
                'notes' => 'Export steel products ke Malaysia',
                'created_by' => $owner->id,
            ]
        );

        ExportOrder::firstOrCreate(
            ['export_order_number' => 'EXP-2026-002'],
            [
                'order_id' => $order4->id,
                'customer_id' => $customer5 ? $customer5->id : 7,
                'order_date' => Carbon::now()->subDays(20),
                'company_id' => $company1 ? $company1->id : 1,
                'shipping_number' => 'SHP-2026-002',
                'do_number' => 'DO-EXP-002',
                'product_name' => 'Steel Pipes',
                'shipping_line' => 'Evergreen Marine',
                'vessel_name' => 'Ever Success',
                'voy_number' => 'V-2338',
                'closing_date' => Carbon::now()->subDays(12),
                'peb_number' => 'PEB-002-2026',
                'depo' => 'TPK KOJA',
                'detention_deadline' => Carbon::now()->subDays(5),
                'pickup_date' => Carbon::now()->subDays(15),
                'return_date' => Carbon::now()->subDays(10),
                'issue' => null,
                'status' => 'completed',
                'created_by' => $owner->id,
            ]
        );
    }
}
