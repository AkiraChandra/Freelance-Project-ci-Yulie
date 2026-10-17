<?php

namespace App\Http\Controllers;

use App\Models\ExportOrder;
use App\Models\ImportOrder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        if (! auth()->user()->hasRole('staff')) {
            return view('dashboard');
        }

        $recentOrders = $this->recentOrders();
        $upcomingDeadlines = $this->upcomingDeadlines();

        return view('dashboard', compact('recentOrders', 'upcomingDeadlines'));
    }

    private function recentOrders(): Collection
    {
        $imports = ImportOrder::with('customer')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get()
            ->map(fn (ImportOrder $order) => [
                'number' => $order->import_order_number,
                'customer' => $order->customer?->customer_name ?? 'Tanpa customer',
                'type' => 'Impor',
                'status' => $order->status,
                'edit_url' => route('import-orders.edit', $order),
                'created_at' => $order->created_at,
            ]);

        $exports = ExportOrder::with('customer')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get()
            ->map(fn (ExportOrder $order) => [
                'number' => $order->export_order_number,
                'customer' => $order->customer?->customer_name ?? 'Tanpa customer',
                'type' => 'Ekspor',
                'status' => $order->status,
                'edit_url' => route('export-orders.edit', $order),
                'created_at' => $order->created_at,
            ]);

        return $imports->concat($exports)
            ->sortByDesc(fn (array $order) => $order['created_at'])
            ->take(3)
            ->values()
            ->map(fn (array $order) => collect($order)->except('created_at')->all());
    }

    private function upcomingDeadlines(): Collection
    {
        $today = Carbon::today();
        $lastDay = $today->copy()->addDays(7);

        $imports = ImportOrder::with('customer')
            ->where('status', 'on going')
            ->whereNotNull('detention_deadline')
            ->whereDate('detention_deadline', '>=', $today)
            ->whereDate('detention_deadline', '<=', $lastDay)
            ->orderBy('detention_deadline')
            ->orderBy('id')
            ->limit(3)
            ->get()
            ->map(fn (ImportOrder $order) => [
                'number' => $order->import_order_number,
                'label' => 'Batas Detention',
                'date' => $order->detention_deadline,
                'edit_url' => route('import-orders.edit', $order),
            ]);

        $exports = ExportOrder::with('customer')
            ->where('status', 'on going')
            ->whereNotNull('closing_date')
            ->whereDate('closing_date', '>=', $today)
            ->whereDate('closing_date', '<=', $lastDay)
            ->orderBy('closing_date')
            ->orderBy('id')
            ->limit(3)
            ->get()
            ->map(fn (ExportOrder $order) => [
                'number' => $order->export_order_number,
                'label' => 'Closing Ekspor',
                'date' => $order->closing_date,
                'edit_url' => route('export-orders.edit', $order),
            ]);

        return $imports->concat($exports)
            ->sortBy(fn (array $deadline) => $deadline['date']->format('Y-m-d'))
            ->take(3)
            ->values();
    }
}
