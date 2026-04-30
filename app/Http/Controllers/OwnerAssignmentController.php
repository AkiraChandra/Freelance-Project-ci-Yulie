<?php

namespace App\Http\Controllers;

use App\Models\OperationalStaffAssignment;
use Illuminate\Http\Request;

class OwnerAssignmentController extends Controller
{
    /**
     * Pending assignments waiting for owner review.
     */
    public function index()
    {
        $assignments = OperationalStaffAssignment::with('operationalStaff', 'assignedBy')
            ->where('status', OperationalStaffAssignment::STATUS_REQUEST)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($a) => tap($a, fn($a) => $a->order));

        return view('owner-assignments.index', compact('assignments'));
    }

    /**
     * History: accepted and declined assignments.
     */
    public function history()
    {
        $assignments = OperationalStaffAssignment::with('operationalStaff', 'assignedBy', 'reviewedBy')
            ->whereIn('status', [
                OperationalStaffAssignment::STATUS_ACCEPTED,
                OperationalStaffAssignment::STATUS_DECLINED,
            ])
            ->orderBy('reviewed_at', 'desc')
            ->get()
            ->map(fn($a) => tap($a, fn($a) => $a->order));

        return view('owner-assignments.history', compact('assignments'));
    }

    /**
     * Approve a pending assignment.
     */
    public function approve(OperationalStaffAssignment $assignment)
    {
        abort_if(!$assignment->isRequest(), 403, 'Penugasan ini sudah difinalisasi.');

        $assignment->update([
            'status'      => OperationalStaffAssignment::STATUS_ACCEPTED,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('owner-assignments.index')
            ->with('success', 'Penugasan berhasil disetujui!');
    }

    /**
     * Decline a pending assignment.
     */
    public function decline(Request $request, OperationalStaffAssignment $assignment)
    {
        abort_if(!$assignment->isRequest(), 403, 'Penugasan ini sudah difinalisasi.');

        $request->validate([
            'rejection_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $assignment->update([
            'status'          => OperationalStaffAssignment::STATUS_DECLINED,
            'reviewed_by'     => auth()->id(),
            'reviewed_at'     => now(),
            'rejection_notes' => $request->filled('rejection_notes') ? $request->rejection_notes : null,
        ]);

        return redirect()->route('owner-assignments.index')
            ->with('success', 'Penugasan telah ditolak dan dipindahkan ke history.');
    }
}
