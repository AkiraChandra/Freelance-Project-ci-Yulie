<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ImportOrder;
use App\Models\ExportOrder;
use App\Models\Vendor;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // ─── Index ──────────────────────────────────────────────────────────────────

    public function index()
    {
        $importOrders = ImportOrder::with('customer', 'creator')
            ->orderBy('created_at', 'desc')->get();
        $exportOrders = ExportOrder::with('customer', 'creator')
            ->orderBy('created_at', 'desc')->get();

        return view('orders.index', compact('importOrders', 'exportOrders'));
    }

    // ─── Select Type ────────────────────────────────────────────────────────────

    public function selectType()
    {
        return view('orders.select-type');
    }

    // ─── IMPORT ─────────────────────────────────────────────────────────────────

    public function createImport()
    {
        $customers = Customer::orderBy('customer_name')->get();
        $vendors = Vendor::where('status', 'active')->orderBy('name')->get();
        return view('orders.import.create', compact('customers', 'vendors'));
    }

    public function storeImport(Request $request)
    {
        $request->validate([
            'customer_id'         => 'required|exists:customers,id',
            'order_date'          => 'required|date',
            'bl_number'           => 'required|string|max:100',
            'product_name'        => 'required|string|max:255',
            'shipping_line'       => 'required|string|max:255',
            'vessel_name'         => 'required|string|max:255',
            'voy_number'          => 'required|string|max:100',
            'vessel_arrival_date' => 'required|date',
            'party'               => 'required|in:20\',40\',LCL,Pallet',
            'status'              => 'required|in:on going,completed,cancelled',
        ], [
            'customer_id.required'         => 'Customer wajib dipilih.',
            'bl_number.required'           => 'B/L No wajib diisi.',
            'product_name.required'        => 'Nama barang wajib diisi.',
            'shipping_line.required'       => 'Pelayaran wajib diisi.',
            'vessel_name.required'         => 'Nama kapal wajib diisi.',
            'voy_number.required'          => 'Voy kapal wajib diisi.',
            'vessel_arrival_date.required' => 'Tgl kapal tiba wajib diisi.',
            'party.required'               => 'Party wajib dipilih.',
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $seq = ImportOrder::where('customer_id', $customer->id)->count() + 1;
        $orderNumber = $customer->customer_code . '/IMP/' . str_pad($seq, 3, '0', STR_PAD_LEFT);

        ImportOrder::create([
            'customer_id'           => $customer->id,
            'import_order_number'   => $orderNumber,
            'order_date'            => $request->order_date,
            'bl_number'             => $request->bl_number,
            'product_name'          => $request->product_name,
            'shipping_line'         => $request->shipping_line,
            'vessel_name'           => $request->vessel_name,
            'voy_number'            => $request->voy_number,
            'vessel_arrival_date'   => $request->vessel_arrival_date,
            'pib_number'            => $request->pib_number,
            'party'                 => $request->party,
            'port'                  => $request->port,
            'do_date'               => $request->do_date,
            'container_number'      => $request->container_number,
            'demurrage_date'        => $request->demurrage_date,
            'release_date'          => $request->release_date,
            'container_return_date' => $request->container_return_date,
            'trucking_vendor'       => implode(', ', array_filter([$request->trucking_vendor_1, $request->trucking_vendor_2])) ?: null,
            'issue'                 => $request->issue,
            'status'                => $request->status ?? 'on going',
            'created_by'            => auth()->id(),
        ]);

        return redirect()->route('orders.index')
            ->with('success', "Order Impor {$orderNumber} berhasil dibuat!");
    }

    public function editImport(ImportOrder $importOrder)
    {
        $customers = Customer::orderBy('customer_name')->get();
        $vendors = Vendor::where('status', 'active')->orderBy('name')->get();
        return view('orders.import.edit', compact('importOrder', 'customers', 'vendors'));
    }

    public function updateImport(Request $request, ImportOrder $importOrder)
    {
        $request->validate([
            'customer_id'         => 'required|exists:customers,id',
            'order_date'          => 'required|date',
            'bl_number'           => 'required|string|max:100',
            'product_name'        => 'required|string|max:255',
            'shipping_line'       => 'required|string|max:255',
            'vessel_name'         => 'required|string|max:255',
            'voy_number'          => 'required|string|max:100',
            'vessel_arrival_date' => 'required|date',
            'party'               => 'required|in:20\',40\',LCL,Pallet',
            'status'              => 'required|in:on going,completed,cancelled',
        ], [
            'customer_id.required'         => 'Customer wajib dipilih.',
            'bl_number.required'           => 'B/L No wajib diisi.',
            'product_name.required'        => 'Nama barang wajib diisi.',
            'shipping_line.required'       => 'Pelayaran wajib diisi.',
            'vessel_name.required'         => 'Nama kapal wajib diisi.',
            'voy_number.required'          => 'Voy kapal wajib diisi.',
            'vessel_arrival_date.required' => 'Tgl kapal tiba wajib diisi.',
            'party.required'               => 'Party wajib dipilih.',
        ]);

        $importOrder->update(array_merge(
            $request->only([
                'customer_id', 'order_date', 'bl_number', 'product_name',
                'shipping_line', 'vessel_name', 'voy_number', 'vessel_arrival_date',
                'pib_number', 'party', 'port', 'do_date', 'container_number',
                'demurrage_date', 'release_date', 'container_return_date',
                'issue', 'status',
            ]),
            ['trucking_vendor' => implode(', ', array_filter([$request->trucking_vendor_1, $request->trucking_vendor_2])) ?: null]
        ));

        return redirect()->route('orders.index')
            ->with('success', "Order Impor {$importOrder->import_order_number} berhasil diupdate!");
    }

    public function destroyImport(ImportOrder $importOrder)
    {
        $number = $importOrder->import_order_number;
        $importOrder->delete();
        return redirect()->route('orders.index')
            ->with('success', "Order Impor {$number} berhasil dihapus!");
    }

    // ─── EXPORT ─────────────────────────────────────────────────────────────────

    public function createExport()
    {
        $customers = Customer::orderBy('customer_name')->get();
        $vendors = Vendor::where('status', 'active')->orderBy('name')->get();
        return view('orders.export.create', compact('customers', 'vendors'));
    }

    public function storeExport(Request $request)
    {
        $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'order_date'    => 'required|date',
            'do_number'     => 'required|string|max:100',
            'product_name'  => 'required|string|max:255',
            'shipping_line' => 'required|string|max:255',
            'vessel_name'   => 'required|string|max:255',
            'voy_number'    => 'required|string|max:100',
            'closing_date'  => 'required|date',
            'party'         => 'required|in:20\',40\',LCL,Pallet',
            'status'        => 'required|in:on going,completed,cancelled',
        ], [
            'customer_id.required'   => 'Customer wajib dipilih.',
            'do_number.required'     => 'No DO wajib diisi.',
            'product_name.required'  => 'Nama barang wajib diisi.',
            'shipping_line.required' => 'Pelayaran wajib diisi.',
            'vessel_name.required'   => 'Nama kapal wajib diisi.',
            'voy_number.required'    => 'Voy kapal wajib diisi.',
            'closing_date.required'  => 'Tgl Clossing wajib diisi.',
            'party.required'         => 'Party wajib dipilih.',
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $seq = ExportOrder::where('customer_id', $customer->id)->count() + 1;
        $orderNumber = $customer->customer_code . '/EXP/' . str_pad($seq, 3, '0', STR_PAD_LEFT);

        ExportOrder::create([
            'customer_id'         => $customer->id,
            'export_order_number' => $orderNumber,
            'order_date'          => $request->order_date,
            'shipping_number'     => $request->shipping_number,
            'do_number'           => $request->do_number,
            'product_name'        => $request->product_name,
            'shipping_line'       => $request->shipping_line,
            'vessel_name'         => $request->vessel_name,
            'voy_number'          => $request->voy_number,
            'closing_date'        => $request->closing_date,
            'peb_number'          => $request->peb_number,
            'party'               => $request->party,
            'depo'                => $request->depo,
            'container_number'    => $request->container_number,
            'pickup_date'         => $request->pickup_date,
            'return_date'         => $request->return_date,
            'trucking_vendor'     => implode(', ', array_filter([$request->trucking_vendor_1, $request->trucking_vendor_2])) ?: null,
            'issue'               => $request->issue,
            'status'              => $request->status ?? 'on going',
            'created_by'          => auth()->id(),
        ]);

        return redirect()->route('orders.index')
            ->with('success', "Order Ekspor {$orderNumber} berhasil dibuat!");
    }

    public function editExport(ExportOrder $exportOrder)
    {
        $customers = Customer::orderBy('customer_name')->get();
        $vendors = Vendor::where('status', 'active')->orderBy('name')->get();
        return view('orders.export.edit', compact('exportOrder', 'customers', 'vendors'));
    }

    public function updateExport(Request $request, ExportOrder $exportOrder)
    {
        $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'order_date'    => 'required|date',
            'do_number'     => 'required|string|max:100',
            'product_name'  => 'required|string|max:255',
            'shipping_line' => 'required|string|max:255',
            'vessel_name'   => 'required|string|max:255',
            'voy_number'    => 'required|string|max:100',
            'closing_date'  => 'required|date',
            'party'         => 'required|in:20\',40\',LCL,Pallet',
            'status'        => 'required|in:on going,completed,cancelled',
        ], [
            'customer_id.required'   => 'Customer wajib dipilih.',
            'do_number.required'     => 'No DO wajib diisi.',
            'product_name.required'  => 'Nama barang wajib diisi.',
            'shipping_line.required' => 'Pelayaran wajib diisi.',
            'vessel_name.required'   => 'Nama kapal wajib diisi.',
            'voy_number.required'    => 'Voy kapal wajib diisi.',
            'closing_date.required'  => 'Tgl Clossing wajib diisi.',
            'party.required'         => 'Party wajib dipilih.',
        ]);

        $exportOrder->update(array_merge(
            $request->only([
                'customer_id', 'order_date', 'shipping_number', 'do_number',
                'product_name', 'shipping_line', 'vessel_name', 'voy_number',
                'closing_date', 'peb_number', 'party', 'depo', 'container_number',
                'pickup_date', 'return_date', 'issue', 'status',
            ]),
            ['trucking_vendor' => implode(', ', array_filter([$request->trucking_vendor_1, $request->trucking_vendor_2])) ?: null]
        ));

        return redirect()->route('orders.index')
            ->with('success', "Order Ekspor {$exportOrder->export_order_number} berhasil diupdate!");
    }

    public function destroyExport(ExportOrder $exportOrder)
    {
        $number = $exportOrder->export_order_number;
        $exportOrder->delete();
        return redirect()->route('orders.index')
            ->with('success', "Order Ekspor {$number} berhasil dihapus!");
    }
}
