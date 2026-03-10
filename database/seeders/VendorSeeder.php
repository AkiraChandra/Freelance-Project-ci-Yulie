<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\VendorPrice;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data vendor dari tabel yang diberikan
        $vendorData = [
            [
                'name' => 'ASST',
                'prices' => [
                    ['lokasi' => 'UG', 'price_20' => 1300000, 'price_40' => 1700000, 'price_2x20' => 2100000],
                    ['lokasi' => 'JATENG', 'price_20' => 1350000, 'price_40' => 1700000, 'price_2x20' => 2300000],
                    ['lokasi' => 'JATIM', 'price_20' => 1600000, 'price_40' => 2000000, 'price_2x20' => 2500000],
                    ['lokasi' => 'MEDISAFE', 'price_20' => 1600000, 'price_40' => 1800000, 'price_2x20' => 2500000],
                    ['lokasi' => 'MEDISAFE SULAWESI', 'price_20' => 1600000, 'price_40' => 1800000, 'price_2x20' => 2500000],
                    ['lokasi' => 'KLAMBIR JAYA', 'price_20' => 1400000, 'price_40' => 1700000, 'price_2x20' => 2300000],
                    ['lokasi' => 'BALIKPAPAN', 'price_20' => 1600000, 'price_40' => 1800000, 'price_2x20' => 2500000],
                ]
            ],
            [
                'name' => 'AK',
                'prices' => [
                    ['lokasi' => 'UG', 'price_20' => 1300000, 'price_40' => 1700000, 'price_2x20' => 2100000],
                    ['lokasi' => 'JATENG', 'price_20' => 1350000, 'price_40' => 1650000, 'price_2x20' => 2000000],
                    ['lokasi' => 'JATIM', 'price_20' => 1600000, 'price_40' => 2000000, 'price_2x20' => 2500000],
                    ['lokasi' => 'MEDISAFE', 'price_20' => 1800000, 'price_40' => 1800000, 'price_2x20' => 2500000],
                    ['lokasi' => 'MEDISAFE SULAWESI', 'price_20' => 1800000, 'price_40' => 1800000, 'price_2x20' => 2500000],
                ]
            ],
            [
                'name' => 'VENDOR CSL',
                'prices' => [
                    ['lokasi' => 'UG', 'price_20' => 1400000, 'price_40' => 1650000, 'price_2x20' => 2100000],
                    ['lokasi' => 'JATENG', 'price_20' => 1400000, 'price_40' => 1700000, 'price_2x20' => 2250000],
                    ['lokasi' => 'JATIM', 'price_20' => 1600000, 'price_40' => 2050000, 'price_2x20' => 2800000],
                    ['lokasi' => 'MEDISAFE', 'price_20' => 1300000, 'price_40' => 1700000, 'price_2x20' => 2100000],
                ]
            ],
            [
                'name' => 'SKT',
                'prices' => [
                    ['lokasi' => 'UG', 'price_20' => 1400000, 'price_40' => 1650000, 'price_2x20' => 2200000],
                    ['lokasi' => 'JATENG', 'price_20' => 1400000, 'price_40' => 1700000, 'price_2x20' => 2300000],
                    ['lokasi' => 'JATIM', 'price_20' => 1600000, 'price_40' => 2000000, 'price_2x20' => 2700000],
                    ['lokasi' => 'MEDISAFE', 'price_20' => 1300000, 'price_40' => 1700000, 'price_2x20' => 2100000],
                ]
            ],
            [
                'name' => 'BJ',
                'prices' => [
                    ['lokasi' => 'UG', 'price_20' => 1300000, 'price_40' => 1650000, 'price_2x20' => 2150000],
                    ['lokasi' => 'JATENG', 'price_20' => 1400000, 'price_40' => 1700000, 'price_2x20' => 2250000],
                    ['lokasi' => 'JATIM', 'price_20' => 1600000, 'price_40' => 1800000, 'price_2x20' => 2700000],
                    ['lokasi' => 'MEDISAFE', 'price_20' => 1600000, 'price_40' => 1800000, 'price_2x20' => 2500000],
                ]
            ],
            [
                'name' => 'ALP',
                'prices' => [
                    ['lokasi' => 'UG', 'price_20' => 1250000, 'price_40' => 1650000, 'price_2x20' => 2150000],
                    ['lokasi' => 'JATENG', 'price_20' => 1400000, 'price_40' => 1700000, 'price_2x20' => 2250000],
                    ['lokasi' => 'JATIM', 'price_20' => 1650000, 'price_40' => 1985000, 'price_2x20' => 2700000],
                ]
            ],
            [
                'name' => 'SAMUDRA',
                'prices' => [
                    ['lokasi' => 'JATENG', 'price_20' => 1400000, 'price_40' => 1700000, 'price_2x20' => 2200000],
                ]
            ],
        ];

        foreach ($vendorData as $vendor) {
            $createdVendor = Vendor::create([
                'name' => $vendor['name'],
                'status' => 'active',
            ]);

            foreach ($vendor['prices'] as $price) {
                VendorPrice::create([
                    'vendor_id' => $createdVendor->id,
                    'lokasi' => $price['lokasi'],
                    'price_20' => $price['price_20'],
                    'price_40' => $price['price_40'],
                    'price_2x20' => $price['price_2x20'],
                    'status' => 'active',
                ]);
            }
        }
    }
}
