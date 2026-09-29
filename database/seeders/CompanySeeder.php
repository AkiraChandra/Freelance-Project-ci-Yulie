<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::where('email', 'akira.chandra@binus.ac.id')->first();

        $companies = [
            [
                'company_code' => 'CMP001',
                'company_name' => 'PT Maju Jaya Steel',
                'pic_name' => 'James Wijaya',
                'phone' => '021-5551234',
                'email' => 'james@majujayasteel.co.id',
                'address' => 'Jl. Industri Raya No. 45, Jakarta Utara',
                'created_by' => $owner->id,
            ],
            [
                'company_code' => 'CMP002',
                'company_name' => 'CV Sumber Rejeki',
                'pic_name' => 'Linda Kusuma',
                'phone' => '022-7512345',
                'email' => 'linda@sumberrejeki.com',
                'address' => 'Jl. Pasteur No. 88, Bandung',
                'created_by' => $owner->id,
            ],
            [
                'company_code' => 'CMP003',
                'company_name' => 'PT Borneo Timber Indonesia',
                'pic_name' => 'Budi Hermawan',
                'phone' => '0541-41234',
                'email' => 'budi@borenotimber.co.id',
                'address' => 'Jl. Trans Kalimantan Km 12, Samarinda',
                'created_by' => $owner->id,
            ],
            [
                'company_code' => 'CMP004',
                'company_name' => 'PT Catur Sentosa Adiprana',
                'pic_name' => 'Rina Marlina',
                'phone' => '031-3981234',
                'email' => 'rina@csaprima.co.id',
                'address' => 'Jl. Margomulyo No. 50, Surabaya',
                'created_by' => $owner->id,
            ],
        ];

        foreach ($companies as $company) {
            Company::firstOrCreate(
                ['company_code' => $company['company_code']],
                $company
            );
        }
    }
}
