<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AssignRolesToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assign Owner role to user ID 1
        $user1 = User::find(1);
        if ($user1) {
            $user1->assignRole('owner');
            echo "✓ User: {$user1->name} ({$user1->email}) assigned role: owner\n";
        }

        // Assign Staff Accounting role to user ID 2
        $user2 = User::find(2);
        if ($user2) {
            $user2->assignRole('staff-accounting');
            echo "✓ User: {$user2->name} ({$user2->email}) assigned role: staff-accounting\n";
        }

        // Assign Staff role to user ID 3
        $user3 = User::find(3);
        if ($user3) {
            $user3->assignRole('staff');
            echo "✓ User: {$user3->name} ({$user3->email}) assigned role: staff\n";
        }

        echo "\n✓ Role assignment completed!\n";
    }
}
