<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Company;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Create new order - show form to choose Import, Export, or Both
     */
    public function selectType()
    {
        return view('orders.select-type');
    }

    /**
     * Create import order - show form
     */
    public function createImport()
    {
        $companies = Company::orderBy('company_code')->get();
        return view('orders.import.create', compact('companies'));
    }

    /**
     * Create export order - show form
     */
    public function createExport()
    {
        $companies = Company::orderBy('company_code')->get();
        return view('orders.export.create', compact('companies'));
    }

    /**
     * Store order with import and/or export data
     */
    public function storeImport(Request $request)
    {
        // Generate order code
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $nextCode = ($lastOrder ? (int)str_replace('ORD-', '', $lastOrder->order_code) : 0) + 1;
        $orderCode = 'ORD-' . str_pad($nextCode, 3, '0', STR_PAD_LEFT);

        // Create main order
        $order = Order::create([
            'order_code' => $orderCode,
            'type' => 'import',
            'order_date' => $request->order_date,
            'company_id' => $request->company_id,
            'created_by' => auth()->id(),
        ]);

        // Create import order data
        $order->importOrder()->create(array_merge($request->except('company_id', 'order_date'), [
            'import_order_number' => 'IMP-' . $orderCode,
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('dashboard')->with('success', "Order Impor {$orderCode} berhasil dibuat!");
    }

    public function storeExport(Request $request)
    {
        // Generate order code
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $nextCode = ($lastOrder ? (int)str_replace('ORD-', '', $lastOrder->order_code) : 0) + 1;
        $orderCode = 'ORD-' . str_pad($nextCode, 3, '0', STR_PAD_LEFT);

        // Create main order
        $order = Order::create([
            'order_code' => $orderCode,
            'type' => 'export',
            'order_date' => $request->order_date,
            'company_id' => $request->company_id,
            'created_by' => auth()->id(),
        ]);

        // Create export order data
        $order->exportOrder()->create(array_merge($request->except('company_id', 'order_date'), [
            'export_order_number' => 'EXP-' . $orderCode,
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('dashboard')->with('success', "Order Ekspor {$orderCode} berhasil dibuat!");
    }
}
