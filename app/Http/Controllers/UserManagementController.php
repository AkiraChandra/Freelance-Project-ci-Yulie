<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $ownerRole = Role::where('name', 'owner')->first();

        $pendingUsers = User::where('status', 'pending')->get();
        $approvedUsers = User::where('status', 'approved')
            ->where(function($query) use ($ownerRole) {
                $query->where('role_id', '!=', $ownerRole->id)
                      ->orWhereNull('role_id');
            })
            ->get();
        $roles = Role::where('name', '!=', 'owner')->get();

        return view('users.manage', compact('pendingUsers', 'approvedUsers', 'roles'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'approved']);

        return back()->with('success', "User {$user->name} berhasil di-approve!");
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;

        $user->delete();

        return back()->with('success', "User {$userName} berhasil di-reject dan akun dihapus. User dapat mendaftar ulang dengan email yang sama.");
    }

    public function assignRole(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id|not_in:' . Role::where('name', 'owner')->first()?->id,
        ]);

        $user = User::findOrFail($id);

        $user->update(['role_id' => $request->role_id]);

        $roleName = Role::find($request->role_id)->name;
        return back()->with('success', "Role {$roleName} berhasil di-assign ke {$user->name}!");
    }

    public function removeRole($id)
    {
        $user = User::findOrFail($id);
        $user->update(['role_id' => null]);

        return back()->with('success', "Role berhasil dihapus dari {$user->name}!");
    }
}
