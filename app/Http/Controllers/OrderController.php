<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ImportOrder;
use App\Models\ExportOrder;
use App\Models\Order;
use App\Models\OrderContainer;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        $importOrders = ImportOrder::with('customer', 'creator')
            ->orderBy('created_at', 'desc')->get();
        $exportOrders = ExportOrder::with('customer', 'creator')
            ->orderBy('created_at', 'desc')->get();

        return view('orders.index', compact('importOrders', 'exportOrders'));
    }

    public function selectType()
    {
        return view('orders.select-type');
    }

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
            'status'              => 'required|in:on going,completed,cancelled',
        ], [
            'customer_id.required'         => 'Customer wajib dipilih.',
            'bl_number.required'           => 'B/L No wajib diisi.',
            'product_name.required'        => 'Nama barang wajib diisi.',
            'shipping_line.required'       => 'Pelayaran wajib diisi.',
            'vessel_name.required'         => 'Nama kapal wajib diisi.',
            'voy_number.required'          => 'Voy kapal wajib diisi.',
            'vessel_arrival_date.required' => 'Tgl kapal tiba wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($request->customer_id);
            $seq = ImportOrder::where('customer_id', $customer->id)->count() + 1;
            $orderNumber = $customer->customer_code . '/IMP/' . str_pad($seq, 3, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_code' => $orderNumber,
                'type' => 'import',
                'order_date' => $request->order_date,
                'status' => $request->status ?? 'on going',
                'created_by' => auth()->id(),
            ]);

            $importOrder = ImportOrder::create([
                'order_id'              => $order->id,
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
                'detention_deadline'    => $request->detention_deadline,
                'demurrage_date'        => $request->demurrage_date,
                'release_date'          => $request->release_date,
                'container_return_date' => $request->container_return_date,
                'trucking_vendor'       => implode(', ', array_filter([$request->trucking_vendor_1, $request->trucking_vendor_2])) ?: null,
                'issue'                 => $request->issue,
                'status'                => $request->status ?? 'on going',
                'created_by'            => auth()->id(),
            ]);

            $this->saveContainers($order->id, 'import', $request);

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', "Order Impor {$orderNumber} berhasil dibuat!");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan order: ' . $e->getMessage()]);
        }
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
            'status'              => 'required|in:on going,completed,cancelled',
        ], [
            'customer_id.required'         => 'Customer wajib dipilih.',
            'bl_number.required'           => 'B/L No wajib diisi.',
            'product_name.required'        => 'Nama barang wajib diisi.',
            'shipping_line.required'       => 'Pelayaran wajib diisi.',
            'vessel_name.required'         => 'Nama kapal wajib diisi.',
            'voy_number.required'          => 'Voy kapal wajib diisi.',
            'vessel_arrival_date.required' => 'Tgl kapal tiba wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $importOrder->update($request->only([
                'customer_id', 'order_date', 'bl_number', 'product_name',
                'shipping_line', 'vessel_name', 'voy_number', 'vessel_arrival_date',
                'pib_number', 'port', 'do_date', 'detention_deadline',
                'demurrage_date', 'release_date', 'container_return_date',
                'issue', 'status',
            ]));

            if ($importOrder->order_id) {
                $this->saveContainers($importOrder->order_id, 'import', $request);
            }

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', "Order Impor {$importOrder->import_order_number} berhasil diupdate!");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Gagal mengupdate order: ' . $e->getMessage()]);
        }
    }

    public function destroyImport(ImportOrder $importOrder)
    {
        $number = $importOrder->import_order_number;
        $importOrder->delete();
        return redirect()->route('orders.index')
            ->with('success', "Order Impor {$number} berhasil dihapus!");
    }

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
            'status'        => 'required|in:on going,completed,cancelled',
        ], [
            'customer_id.required'   => 'Customer wajib dipilih.',
            'do_number.required'     => 'No DO wajib diisi.',
            'product_name.required'  => 'Nama barang wajib diisi.',
            'shipping_line.required' => 'Pelayaran wajib diisi.',
            'vessel_name.required'   => 'Nama kapal wajib diisi.',
            'voy_number.required'    => 'Voy kapal wajib diisi.',
            'closing_date.required'  => 'Tgl Clossing wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($request->customer_id);
            $seq = ExportOrder::where('customer_id', $customer->id)->count() + 1;
            $orderNumber = $customer->customer_code . '/EXP/' . str_pad($seq, 3, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_code' => $orderNumber,
                'type' => 'export',
                'order_date' => $request->order_date,
                'status' => $request->status ?? 'on going',
                'created_by' => auth()->id(),
            ]);

            ExportOrder::create([
                'order_id'            => $order->id,
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
                'detention_deadline'  => $request->detention_deadline,
                'peb_number'          => $request->peb_number,
                'depo'                => $request->depo,
                'pickup_date'         => $request->pickup_date,
                'return_date'         => $request->return_date,
                'issue'               => $request->issue,
                'status'              => $request->status ?? 'on going',
                'created_by'          => auth()->id(),
            ]);

            $this->saveContainers($order->id, 'export', $request);

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', "Order Ekspor {$orderNumber} berhasil dibuat!");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan order: ' . $e->getMessage()]);
        }
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
            'status'        => 'required|in:on going,completed,cancelled',
        ], [
            'customer_id.required'   => 'Customer wajib dipilih.',
            'do_number.required'     => 'No DO wajib diisi.',
            'product_name.required'  => 'Nama barang wajib diisi.',
            'shipping_line.required' => 'Pelayaran wajib diisi.',
            'vessel_name.required'   => 'Nama kapal wajib diisi.',
            'voy_number.required'    => 'Voy kapal wajib diisi.',
            'closing_date.required'  => 'Tgl Clossing wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $exportOrder->update($request->only([
                'customer_id', 'order_date', 'shipping_number', 'do_number',
                'product_name', 'shipping_line', 'vessel_name', 'voy_number',
                'closing_date', 'detention_deadline', 'peb_number', 'depo',
                'pickup_date', 'return_date', 'issue', 'status',
            ]));

            if ($exportOrder->order_id) {
                $this->saveContainers($exportOrder->order_id, 'export', $request);
            }

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', "Order Ekspor {$exportOrder->export_order_number} berhasil diupdate!");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Gagal mengupdate order: ' . $e->getMessage()]);
        }
    }

    public function destroyExport(ExportOrder $exportOrder)
    {
        $number = $exportOrder->export_order_number;
        $exportOrder->delete();
        return redirect()->route('orders.index')
            ->with('success', "Order Ekspor {$number} berhasil dihapus!");
    }

    /**
     * Update container release and return dates
     */
    public function updateContainerDates(Request $request, OrderContainer $container)
    {
        $request->validate([
            'release_date' => 'nullable|date',
            'return_date'  => 'nullable|date',
        ], [
            'release_date.date' => 'Format tanggal tidak valid.',
            'return_date.date'  => 'Format tanggal tidak valid.',
        ]);

        // Validate: release_date tidak boleh sebelum do_date
        $order = $container->order;
        $doDate = null;

        if ($order) {
            if ($container->order_type === 'import') {
                $importOrder = ImportOrder::find($order->id);
                $doDate = $importOrder ? $importOrder->do_date : null;
            } else {
                $exportOrder = ExportOrder::find($order->id);
                $doDate = $exportOrder ? $exportOrder->closing_date : null;
            }
        }

        if ($request->release_date && $doDate) {
            $releaseDate = Carbon::parse($request->release_date);
            if ($releaseDate->lt(Carbon::parse($doDate))) {
                return back()->withErrors([
                    'release_date' => 'Tgl Pengeluaran tidak boleh sebelum Tgl DO.'
                ]);
            }
        }

        $container->update([
            'release_date' => $request->release_date,
            'return_date'  => $request->return_date,
        ]);

        return back()->with('success', 'Data container berhasil diupdate!');
    }

    protected function saveContainers($orderId, $orderType, Request $request)
    {
        // Delete existing containers for this order
        OrderContainer::where('order_id', $orderId)->where('order_type', $orderType)->delete();

        $groups = $request->input('groups', []);

        if (empty($groups)) {
            return;
        }

        $containerMap = []; // Maps container_number => container_id for current order
        $containerObjects = []; // Store container objects for bidirectional relationship
        $globalIndex = 1;

        // First pass: Create all containers
        foreach ($groups as $group) {
            $size = $group['size'] ?? null;

            if ($size === 'LCL') {
                $quantity = max(1, (int) ($group['quantity'] ?? 1));
                $lclVendor = $group['lcl_vendor'] ?? null;
                for ($i = 0; $i < $quantity; $i++) {
                    OrderContainer::create([
                        'order_id' => $orderId,
                        'order_type' => $orderType,
                        'container_group' => $globalIndex,
                        'container_size' => 'LCL',
                        'container_number' => null,
                        'container_type' => null,
                        'vendor' => $lclVendor,
                        'combo_with' => null,
                        'combine_with_container_id' => null,
                    ]);
                }
                $globalIndex++;
                continue;
            }

            $containers = $group['containers'] ?? [];
            foreach ($containers as $containerData) {
                $containerNumber = trim((string) ($containerData['number'] ?? ''));
                $container = OrderContainer::create([
                    'order_id' => $orderId,
                    'order_type' => $orderType,
                    'container_group' => $globalIndex,
                    'container_size' => $size,
                    'container_number' => $containerNumber !== '' ? $containerNumber : null,
                    'container_type' => $containerData['type'] ?? null,
                    'vendor' => $containerData['vendor'] ?? null,
                    'combo_with' => null,
                    'combine_with_container_id' => null,
                ]);

                if ($containerNumber !== '') {
                    $containerMap[$containerNumber] = $container->id;
                    $containerObjects[$container->id] = $container;
                }
            }
            $globalIndex++;
        }

        // Second pass: Resolve COMBO references (same order, bidirectional)
        foreach ($groups as $group) {
            if (($group['size'] ?? '') === 'LCL') {
                continue;
            }
            $containers = $group['containers'] ?? [];
            foreach ($containers as $containerData) {
                $containerNumber = trim((string) ($containerData['number'] ?? ''));
                $comboContainerNumber = trim((string) ($containerData['combo'] ?? ''));

                // COMBO: Must be in same order, same size, same type, same vendor, different number
                if ($containerNumber !== '' && $comboContainerNumber !== '' && isset($containerMap[$comboContainerNumber])) {
                    $sourceId = $containerMap[$containerNumber];
                    $targetId = $containerMap[$comboContainerNumber];

                    // Update bidirectional relationship
                    OrderContainer::where('id', $sourceId)->update(['combo_with' => $targetId]);
                    OrderContainer::where('id', $targetId)->update(['combo_with' => $sourceId]);
                }
            }
        }

        // Third pass: Resolve COMBINE references (different order, bidirectional)
        foreach ($groups as $group) {
            if (($group['size'] ?? '') === 'LCL') {
                continue;
            }
            $containers = $group['containers'] ?? [];
            foreach ($containers as $containerData) {
                $containerNumber = trim((string) ($containerData['number'] ?? ''));
                $combineValue = trim((string) ($containerData['combine'] ?? ''));

                // COMBINE: Format is "order_code/container_number"
                if ($containerNumber !== '' && $combineValue !== '') {
                    $parts = explode('/', $combineValue, 2);
                    if (count($parts) === 2) {
                        $targetOrderCode = $parts[0];
                        $targetContainerNumber = $parts[1];

                        // Find the target order
                        $targetOrder = Order::where('order_code', $targetOrderCode)
                            ->where('type', $orderType)
                            ->first();

                        if ($targetOrder) {
                            // Find the target container
                            $targetContainer = OrderContainer::where('order_id', $targetOrder->id)
                                ->where('order_type', $orderType)
                                ->where('container_number', $targetContainerNumber)
                                ->first();

                            if ($targetContainer) {
                                $sourceId = $containerMap[$containerNumber];
                                $targetId = $targetContainer->id;

                                // Update bidirectional relationship
                                OrderContainer::where('id', $sourceId)->update(['combine_with_container_id' => $targetId]);
                                OrderContainer::where('id', $targetId)->update(['combine_with_container_id' => $sourceId]);
                            }
                        }
                    }
                }
            }
        }
    }
}
