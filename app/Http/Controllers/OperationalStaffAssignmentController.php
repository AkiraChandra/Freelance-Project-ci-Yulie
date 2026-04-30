<?php

namespace App\Http\Controllers;

use App\Models\ExportOrder;
use App\Models\ImportOrder;
use App\Models\OperationalStaff;
use App\Models\OperationalStaffAssignment;
use Illuminate\Http\Request;

class OperationalStaffAssignmentController extends Controller
{
    public function index()
    {
        $assignments = OperationalStaffAssignment::with('operationalStaff', 'assignedBy')
            ->where('assigned_by', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($assignment) {
                $assignment->order = $assignment->order; // trigger accessor
                return $assignment;
            });

        return view('staff-assignments.index', compact('assignments'));
    }

    public function create()
    {
        $staffs       = OperationalStaff::where('status', 'active')->orderBy('name')->get();
        $exportOrders = ExportOrder::with('customer')->orderBy('export_order_number')->get();
        $importOrders = ImportOrder::with('customer')->orderBy('import_order_number')->get();

        // Build a map: { staffId: { export: [orderId,...], import: [orderId,...] } }
        // Only include active (request/accepted) assignments — declined can be re-assigned
        $assignedMap = OperationalStaffAssignment::whereIn('status', [
                \App\Models\OperationalStaffAssignment::STATUS_REQUEST,
                \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED,
            ])
            ->get()
            ->groupBy('operational_staff_id')
            ->map(fn($group) => [
                'export' => $group->where('order_type', 'export')->pluck('order_id')->values(),
                'import' => $group->where('order_type', 'import')->pluck('order_id')->values(),
            ]);

        return view('staff-assignments.create', compact('staffs', 'exportOrders', 'importOrders', 'assignedMap'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'operational_staff_id' => 'required|exists:operational_staffs,id',
            'order_type'           => 'required|in:export,import',
            'order_id'             => 'required|integer',
            'fee'                  => 'required|numeric|min:0',
            'notes'                => 'nullable|string|max:500',
        ], [
            'operational_staff_id.required' => 'Staff operasional wajib dipilih.',
            'order_type.required'           => 'Tipe order wajib dipilih.',
            'order_id.required'             => 'Order wajib dipilih.',
            'fee.required'                  => 'Biaya wajib diisi.',
            'fee.min'                       => 'Biaya tidak boleh negatif.',
        ]);

        // Validate order exists
        if ($request->order_type === 'export') {
            $orderExists = ExportOrder::where('id', $request->order_id)->exists();
        } else {
            $orderExists = ImportOrder::where('id', $request->order_id)->exists();
        }

        if (!$orderExists) {
            return back()->withErrors(['order_id' => 'Order yang dipilih tidak ditemukan.'])->withInput();
        }

        // Validate staff has not been assigned to this exact order before (ignore declined — can be re-assigned)
        $alreadyAssigned = OperationalStaffAssignment::where('operational_staff_id', $request->operational_staff_id)
            ->where('order_type', $request->order_type)
            ->where('order_id', $request->order_id)
            ->whereIn('status', [
                \App\Models\OperationalStaffAssignment::STATUS_REQUEST,
                \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED,
            ])
            ->exists();

        if ($alreadyAssigned) {
            $staff = OperationalStaff::find($request->operational_staff_id);
            $staffName = $staff?->name ?? 'Staff ini';
            return back()
                ->withErrors(['order_id' => "{$staffName} sudah pernah di-assign ke order ini. Satu staff hanya bisa di-assign satu kali per order."])
                ->withInput();
        }

        OperationalStaffAssignment::create([
            'operational_staff_id' => $request->operational_staff_id,
            'order_type'           => $request->order_type,
            'order_id'             => $request->order_id,
            'fee'                  => $request->fee,
            'notes'                => $request->notes,
            'assigned_by'          => auth()->id(),
        ]);

        return redirect()->route('staff-assignments.index')
            ->with('success', 'Assignment staff operasional berhasil disimpan!');
    }

    public function destroy(OperationalStaffAssignment $staffAssignment)
    {
        if ($staffAssignment->isFinalized()) {
            return redirect()->route('staff-assignments.index')
                ->with('error', 'Penugasan yang sudah difinalisasi tidak dapat dihapus.');
        }

        $staffAssignment->delete();
        return redirect()->route('staff-assignments.index')
            ->with('success', 'Assignment berhasil dihapus!');
    }
}
