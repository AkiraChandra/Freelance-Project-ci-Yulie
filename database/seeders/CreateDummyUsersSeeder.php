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
        // Create roles first if they don't exist
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $staffAccRole = Role::firstOrCreate(['name' => 'staff-accounting', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

        // Owner
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'akira.chandra@binus.ac.id',
            'password' => Hash::make('87654321'),
            'status' => 'approved',
            'role_id' => 1,
        ]);
        $owner->assignRole($ownerRole);

        // Staff Accounting
        $staffAcc = User::create([
            'name' => 'Staff Accounting',
            'email' => 'akirasanchandra2@gmail.com',
            'password' => Hash::make('87654321'),
            'status' => 'approved',
            'role_id' => 2,
        ]);
        $staffAcc->assignRole($staffAccRole);

        // Staff Biasa
        $staff = User::create([
            'name' => 'Staff Biasa',
            'email' => 'akira.chandra@binus.edu',
            'password' => Hash::make('87654321'),
            'status' => 'approved',
            'role_id' => 3,
        ]);
        $staff->assignRole($staffRole);
    }
}
