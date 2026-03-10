<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use DB;

class MigrateRolesToRoleIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Migrate roles from model_has_roles to role_id column
        $users = User::all();
        
        foreach ($users as $user) {
            // Get first role from pivot table
            $role = DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->select('roles.id')
                ->first();
            
            if ($role) {
                $user->update(['role_id' => $role->id]);
            }
        }
        
        echo "✓ Data roles berhasil di-migrate ke role_id column\n";
    }
}
