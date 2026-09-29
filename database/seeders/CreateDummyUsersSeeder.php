<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateDummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $accountantRole = Role::firstOrCreate(['name' => 'staff-accounting', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

        // ============================================
        // USER: Owner
        // Full access - can see all data
        // Password: 87654321
        // ============================================
        $ownerUser = User::firstOrCreate(
            ['email' => 'akira.chandra@binus.ac.id'],
            [
                'name' => 'Owner',
                'password' => Hash::make('87654321'),
                'status' => 'approved',
                'role_id' => 1,
            ]
        );
        $ownerUser->assignRole($ownerRole);

        // ============================================
        // USER: Accountant (Akuntan)
        // Can view orders, manage bookkeeping
        // Password: 87654321
        // ============================================
        $accountantUser = User::firstOrCreate(
            ['email' => 'akirasanchandra2@gmail.com'],
            [
                'name' => 'Staff Accounting',
                'password' => Hash::make('87654321'),
                'status' => 'approved',
                'role_id' => 2,
            ]
        );
        $accountantUser->assignRole($accountantRole);

        // ============================================
        // USER: Staff (Regular Staff)
        // Can export/import data
        // Password: 87654321
        // ============================================
        $staffUser = User::firstOrCreate(
            ['email' => 'akira.chandra@binus.edu'],
            [
                'name' => 'Staff Biasa',
                'password' => Hash::make('87654321'),
                'status' => 'approved',
                'role_id' => 3,
            ]
        );
        $staffUser->assignRole($staffRole);
    }
}
