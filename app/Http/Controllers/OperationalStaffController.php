<?php

namespace App\Http\Controllers;

use App\Models\OperationalStaff;
use Illuminate\Http\Request;

class OperationalStaffController extends Controller
{
    public function index()
    {
        $staffs = OperationalStaff::orderBy('name')->get();
        return view('operational-staff.index', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'nullable|string|max:50',
            'status'  => 'required|in:active,inactive',
        ], [
            'name.required' => 'Nama staff wajib diisi.',
        ]);

        OperationalStaff::create([
            'name'       => $request->name,
            'contact'    => $request->contact,
            'status'     => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('operational-staff.index')
            ->with('success', "Staff operasional {$request->name} berhasil ditambahkan!");
    }

    public function update(Request $request, OperationalStaff $operationalStaff)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'nullable|string|max:50',
            'status'  => 'required|in:active,inactive',
        ], [
            'name.required' => 'Nama staff wajib diisi.',
        ]);

        $operationalStaff->update([
            'name'    => $request->name,
            'contact' => $request->contact,
            'status'  => $request->status,
        ]);

        return redirect()->route('operational-staff.index')
            ->with('success', "Data staff {$operationalStaff->name} berhasil diperbarui!");
    }

    public function destroy(OperationalStaff $operationalStaff)
    {
        $name = $operationalStaff->name;
        $operationalStaff->delete();

        return redirect()->route('operational-staff.index')
            ->with('success', "Staff operasional {$name} berhasil dihapus!");
    }
}
