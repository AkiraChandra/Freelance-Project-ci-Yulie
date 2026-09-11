<?php

namespace App\Http\Controllers;

use App\Models\ExportOrder;
use App\Models\Company;
use Illuminate\Http\Request;

class ExportOrderController extends Controller
{
    public function create()
    {
        $companies = Company::orderBy('company_code')->get();
        return view('orders.export.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|unique:export_orders',
            'order_date' => 'required|date',
            'company_id' => 'required|exists:companies,id',
            'shipping_number' => 'nullable|string',
            'do_number' => 'nullable|string',
            'product_name' => 'nullable|string',
            'shipping_line' => 'nullable|string',
            'vessel_name' => 'nullable|string',
            'voy_number' => 'nullable|string',
            'closing_date' => 'nullable|date',
            'peb_number' => 'nullable|string',
            'party' => 'nullable|string',
            'depo' => 'nullable|string',
            'container_number' => 'nullable|string',
            'pickup_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'trucking_vendor' => 'nullable|string',
            'issue' => 'nullable|string',
        ]);

        ExportOrder::create(array_merge($request->all(), ['created_by' => auth()->id()]));

        return redirect()->route('dashboard')->with('success', "Order Ekspor {$request->order_number} berhasil dibuat!");
    }
}
