<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\ImportOrder;
use App\Models\ExportOrder;
use App\Models\User;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::where('email', 'akira.chandra@binus.ac.id')->first();
        $accountant = User::where('email', 'akirasanchandra2@gmail.com')->first();

        $importOrder1 = ImportOrder::where('import_order_number', 'IMP-2026-001')->first();
        $exportOrder2 = ExportOrder::where('export_order_number', 'EXP-2026-002')->first();

        // ============================================
        // INVOICE 1 - Import Order 1 (On Going)
        // ============================================
        if ($importOrder1) {
            Invoice::firstOrCreate(
                ['nota_number' => 'IMP-2026-001'],
                [
                    'order_type' => 'import',
                    'order_id' => $importOrder1->id,
                    'invoice_title' => 'Invoice Handling & Trucking Services',
                    'vessel_name' => 'MV Western Jakarta',
                    'vessel_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                    'destination' => 'PT Maju Jaya Steel - Jl. Industri Raya No. 45',
                    'party_display' => '20\'',
                    'product_name' => 'Hot Rolled Steel Coil',
                    'container_display' => '1x20 GP MSCU1234567',
                    'revision' => 0,
                    'sections' => [
                        [
                            'name' => 'Demurrage Charges',
                            'items' => [
                                ['label' => 'Demurrage 1-3 days (3 days x Rp 500,000)', 'amount' => 1500000],
                                ['label' => 'Extension day 4', 'amount' => 600000],
                                ['label' => 'Extension day 5', 'amount' => 650000],
                            ]
                        ],
                        [
                            'name' => 'Trucking Charges',
                            'items' => [
                                ['label' => 'Trucking Tanjung Priok - Jakarta Utara (1x20)', 'amount' => 3500000],
                                ['label' => 'Handling fee', 'amount' => 500000],
                            ]
                        ],
                        [
                            'name' => 'Administration',
                            'items' => [
                                ['label' => 'Admin fee', 'amount' => 250000],
                                ['label' => 'Documentation', 'amount' => 150000],
                            ]
                        ],
                    ],
                    'panjar' => 3000000,
                    'include_tax' => 1,
                    'tax_percentage' => 1.10,
                    'created_by' => $accountant->id,
                ]
            );

            // Invoice 2 - Revision A for Import Order 1
            Invoice::firstOrCreate(
                ['nota_number' => 'IMP-2026-001-A'],
                [
                    'order_type' => 'import',
                    'order_id' => $importOrder1->id,
                    'invoice_title' => 'Invoice Additional Charges',
                    'vessel_name' => 'MV Western Jakarta',
                    'vessel_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                    'destination' => 'PT Maju Jaya Steel - Jl. Industri Raya No. 45',
                    'party_display' => '20\'',
                    'product_name' => 'Hot Rolled Steel Coil',
                    'container_display' => '1x20 GP MSCU1234567',
                    'revision' => 1,
                    'sections' => [
                        [
                            'name' => 'Additional Charges',
                            'items' => [
                                ['label' => 'Overtime handling', 'amount' => 350000],
                                ['label' => 'Weighing fee', 'amount' => 150000],
                            ]
                        ],
                    ],
                    'panjar' => 0,
                    'include_tax' => 1,
                    'tax_percentage' => 1.10,
                    'created_by' => $accountant->id,
                ]
            );
        }

        // ============================================
        // INVOICE 3 - Export Order 2 (Completed)
        // ============================================
        if ($exportOrder2) {
            Invoice::firstOrCreate(
                ['nota_number' => 'EXP-2026-002'],
                [
                    'order_type' => 'export',
                    'order_id' => $exportOrder2->id,
                    'invoice_title' => 'Invoice Export Steel Pipes - Completed',
                    'vessel_name' => 'Ever Success',
                    'vessel_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                    'destination' => 'Port Klang, Malaysia',
                    'party_display' => '20\'',
                    'product_name' => 'Steel Pipes',
                    'container_display' => '2x20 GP EGHU7788990',
                    'revision' => 0,
                    'sections' => [
                        [
                            'name' => 'Container Services',
                            'items' => [
                                ['label' => 'Container pickup (2x20)', 'amount' => 3000000],
                                ['label' => 'Factory delivery (2 trips)', 'amount' => 4000000],
                                ['label' => 'Loading supervision', 'amount' => 1000000],
                            ]
                        ],
                        [
                            'name' => 'Documentation',
                            'items' => [
                                ['label' => 'PEB & Customs', 'amount' => 1500000],
                                ['label' => 'Export permit', 'amount' => 500000],
                                ['label' => 'Bill of Lading processing', 'amount' => 750000],
                            ]
                        ],
                        [
                            'name' => 'Port & Terminal',
                            'items' => [
                                ['label' => 'TPK KOJA handling', 'amount' => 2500000],
                                ['label' => 'THC (Terminal Handling Charge)', 'amount' => 1800000],
                                ['label' => 'Seal & safety inspection', 'amount' => 300000],
                            ]
                        ],
                    ],
                    'panjar' => 8000000,
                    'include_tax' => 1,
                    'tax_percentage' => 1.10,
                    'created_by' => $accountant->id,
                ]
            );
        }
    }
}
