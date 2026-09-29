<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::where('email', 'akira.chandra@binus.ac.id')->first();

        $customers = [
            // Existing customers from dump - preserve their codes
            [
                'customer_code' => '26161',
                'customer_name' => 'PT Maju Bersama',
                'type' => 'ekspor',
                'city' => 'MEDAN',
                'created_by' => $owner->id,
            ],
            [
                'customer_code' => '89186',
                'customer_name' => 'PT Maju Bersamas',
                'type' => 'impor',
                'city' => 'MEDAN',
                'created_by' => $owner->id,
            ],
            // Additional customers that may not exist yet
            [
                'customer_code' => 'C0001',
                'customer_name' => 'PT Petrokimia Gresik',
                'type' => 'impor',
                'city' => 'Gresik',
                'created_by' => $owner->id,
            ],
            [
                'customer_code' => 'C0002',
                'customer_name' => 'PT Pupuk Kalimantan Timur',
                'type' => 'impor',
                'city' => 'Bontang',
                'created_by' => $owner->id,
            ],
            [
                'customer_code' => 'C0006',
                'customer_name' => 'PT Astra International',
                'type' => 'ekspor',
                'city' => 'Jakarta',
                'created_by' => $owner->id,
            ],
            [
                'customer_code' => 'C0007',
                'customer_name' => 'PT Salim Ivomas Pratama',
                'type' => 'ekspor',
                'city' => 'Jakarta',
                'created_by' => $owner->id,
            ],
        ];

        foreach ($customers as $customer) {
            // Check if customer with this code or name already exists
            $exists = Customer::where('customer_code', $customer['customer_code'])
                ->orWhere('customer_name', $customer['customer_name'])
                ->exists();

            if (!$exists) {
                Customer::create($customer);
            }
        }
    }
}
