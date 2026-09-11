<?php

namespace App\Http\Controllers;

use App\Models\OperationalExpense;
use App\Models\OperationalStaffAssignment;
use Illuminate\Http\Request;

class OperationalExpenseController extends Controller
{
    public function index(OperationalStaffAssignment $assignment)
    {
        $expenses = $assignment->expenses()->orderBy('expense_date', 'desc')->get();
        $totalExpenses = $expenses->sum('amount');

        return view('staff-assignments.expenses.index', compact('assignment', 'expenses', 'totalExpenses'));
    }

    public function store(Request $request, OperationalStaffAssignment $assignment)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'amount'       => 'required|numeric|min:0',
            'description'  => 'required|string|max:255',
        ], [
            'expense_date.required' => 'Tanggal biaya wajib diisi.',
            'amount.required'       => 'Nominal wajib diisi.',
            'amount.min'            => 'Nominal tidak boleh negatif.',
            'description.required'  => 'Keterangan wajib diisi.',
        ]);

        OperationalExpense::create([
            'assignment_id' => $assignment->id,
            'expense_date'  => $request->expense_date,
            'amount'        => $request->amount,
            'description'   => $request->description,
            'created_by'    => auth()->id(),
        ]);

        return back()->with('success', 'Biaya berhasil ditambahkan!');
    }

    public function destroy(OperationalStaffAssignment $assignment, OperationalExpense $expense)
    {
        if ($expense->assignment_id !== $assignment->id) {
            return back()->with('error', 'Biaya tidak ditemukan dalam assignment ini.');
        }

        if ($assignment->isFinalized()) {
            return back()->with('error', 'Tidak dapat menghapus biaya pada assignment yang sudah difinalisasi.');
        }

        $expense->delete();
        return back()->with('success', 'Biaya berhasil dihapus!');
    }
}
