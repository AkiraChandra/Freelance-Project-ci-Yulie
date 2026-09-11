<?php

namespace App\Http\Controllers;

use App\Models\ImportOrder;
use App\Models\Company;
use Illuminate\Http\Request;

class ImportOrderController extends Controller
{
    public function create()
    {
        $companies = Company::orderBy('company_code')->get();
        return view('orders.import.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|unique:import_orders',
            'order_date' => 'required|date',
            'company_id' => 'required|exists:companies,id',
            'bl_number' => 'nullable|string',
            'product_name' => 'nullable|string',
            'shipping_line' => 'nullable|string',
            'vessel_name' => 'nullable|string',
            'voy_number' => 'nullable|string',
            'vessel_arrival_date' => 'nullable|date',
            'pib_number' => 'nullable|string',
            'party' => 'nullable|string',
            'port' => 'nullable|string',
            'do_date' => 'nullable|date',
            'container_number' => 'nullable|string',
            'demurrage_date' => 'nullable|date',
            'release_date' => 'nullable|date',
            'container_return_date' => 'nullable|date',
            'trucking_vendor' => 'nullable|string',
            'issue' => 'nullable|string',
        ]);

        ImportOrder::create(array_merge($request->all(), ['created_by' => auth()->id()]));

        return redirect()->route('dashboard')->with('success', "Order Impor {$request->order_number} berhasil dibuat!");
    }
}
