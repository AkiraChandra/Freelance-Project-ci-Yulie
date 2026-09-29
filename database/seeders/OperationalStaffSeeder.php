<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OperationalStaff;
use App\Models\OperationalStaffAssignment;
use App\Models\ImportOrder;
use App\Models\ExportOrder;
use App\Models\User;
use Carbon\Carbon;

class OperationalStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::where('email', 'akira.chandra@binus.ac.id')->first();
        $staffAcc = User::where('email', 'akirasanchandra2@gmail.com')->first();
        $staff = User::where('email', 'akira.chandra@binus.edu')->first();

        // ============================================
        // OPERATIONAL STAFFS
        // ============================================
        $staffs = [
            [
                'name' => 'Joko Pramono',
                'contact' => '0812-3456-7890',
                'status' => 'active',
                'created_by' => $owner->id,
            ],
            [
                'name' => 'Tono Susanto',
                'contact' => '0813-4567-8901',
                'status' => 'active',
                'created_by' => $owner->id,
            ],
            [
                'name' => 'Bayu Firmansyah',
                'contact' => '0814-5678-9012',
                'status' => 'active',
                'created_by' => $owner->id,
            ],
        ];

        foreach ($staffs as $staffData) {
            OperationalStaff::firstOrCreate(
                ['name' => $staffData['name']],
                $staffData
            );
        }

        // ============================================
        // OPERATIONAL STAFF ASSIGNMENTS
        // ============================================
        $importOrder1 = ImportOrder::where('import_order_number', 'IMP-2026-001')->first();
        $importOrder2 = ImportOrder::where('import_order_number', 'IMP-2026-002')->first();
        $exportOrder1 = ExportOrder::where('export_order_number', 'EXP-2026-001')->first();
        $exportOrder2 = ExportOrder::where('export_order_number', 'EXP-2026-002')->first();

        $opStaff1 = OperationalStaff::where('name', 'Joko Pramono')->first();
        $opStaff2 = OperationalStaff::where('name', 'Tono Susanto')->first();
        $opStaff3 = OperationalStaff::where('name', 'Bayu Firmansyah')->first();

        // Assignment 1 - Accepted (Joko - Import Order 1)
        if ($importOrder1 && $opStaff1) {
            OperationalStaffAssignment::firstOrCreate(
                [
                    'operational_staff_id' => $opStaff1->id,
                    'order_type' => 'import',
                    'order_id' => $importOrder1->id,
                ],
                [
                    'fee' => 2500000,
                    'notes' => 'Handle trucking dari Tanjung Priok ke pabrik',
                    'status' => OperationalStaffAssignment::STATUS_ACCEPTED,
                    'assigned_by' => $owner->id,
                    'reviewed_by' => $owner->id,
                    'reviewed_at' => Carbon::now()->subDays(8),
                ]
            );
        }

        // Assignment 2 - Request/Pending (Tono - Import Order 2)
        if ($importOrder2 && $opStaff2) {
            OperationalStaffAssignment::firstOrCreate(
                [
                    'operational_staff_id' => $opStaff2->id,
                    'order_type' => 'import',
                    'order_id' => $importOrder2->id,
                ],
                [
                    'fee' => 3500000,
                    'notes' => 'Handle trucking dari Semayang ke pabrik di Bontang',
                    'status' => OperationalStaffAssignment::STATUS_REQUEST,
                    'assigned_by' => $staffAcc->id,
                ]
            );
        }

        // Assignment 3 - Accepted (Bayu - Export Order 1)
        if ($exportOrder1 && $opStaff3) {
            OperationalStaffAssignment::firstOrCreate(
                [
                    'operational_staff_id' => $opStaff3->id,
                    'order_type' => 'export',
                    'order_id' => $exportOrder1->id,
                ],
                [
                    'fee' => 2000000,
                    'notes' => 'Handle trucking pickup container dari depo',
                    'status' => OperationalStaffAssignment::STATUS_ACCEPTED,
                    'assigned_by' => $owner->id,
                    'reviewed_by' => $owner->id,
                    'reviewed_at' => Carbon::now()->subDays(3),
                ]
            );
        }

        // Assignment 4 - Declined (Joko - Export Order 2)
        if ($exportOrder2 && $opStaff1) {
            OperationalStaffAssignment::firstOrCreate(
                [
                    'operational_staff_id' => $opStaff1->id,
                    'order_type' => 'export',
                    'order_id' => $exportOrder2->id,
                ],
                [
                    'fee' => 2800000,
                    'notes' => 'Handle trucking export ke TPK KOJA',
                    'status' => OperationalStaffAssignment::STATUS_DECLINED,
                    'assigned_by' => $owner->id,
                    'reviewed_by' => $owner->id,
                    'reviewed_at' => Carbon::now()->subDays(15),
                    'rejection_notes' => 'Staff dialihkan ke tugas lain',
                ]
            );
        }
    }
}
