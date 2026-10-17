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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
        $availableCombineContainers = $this->availableCombineContainers();
        return view('orders.import.create', compact('customers', 'vendors', 'availableCombineContainers'));
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
            'do_date'             => 'nullable|date',
            'detention_deadline'  => 'nullable|date',
            'demurrage_date'      => 'nullable|date',
            'release_date'        => 'nullable|date',
            'container_return_date' => 'nullable|date',
        ], [
            'customer_id.required'         => 'Customer wajib dipilih.',
            'bl_number.required'           => 'B/L No wajib diisi.',
            'product_name.required'        => 'Nama barang wajib diisi.',
            'shipping_line.required'       => 'Pelayaran wajib diisi.',
            'vessel_name.required'         => 'Nama kapal wajib diisi.',
            'voy_number.required'          => 'Voy kapal wajib diisi.',
            'vessel_arrival_date.required' => 'Tgl kapal tiba wajib diisi.',
        ]);

        $this->validateContainerGroups($request, 'import', null, true);

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
                'port'                  => $request->port,
                'do_date'               => $request->do_date,
                'detention_deadline'    => $request->detention_deadline,
                'demurrage_date'        => $request->demurrage_date,
                'release_date'          => $request->release_date,
                'container_return_date' => $request->container_return_date,
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
        $existingGroups = $this->containerGroups($importOrder->order_id, 'import');
        $availableCombineContainers = $this->availableCombineContainers($importOrder->order_id, $importOrder->customer_id);

        return view('orders.import.edit', compact('importOrder', 'customers', 'vendors', 'existingGroups', 'availableCombineContainers'));
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
            'do_date'             => 'nullable|date',
            'detention_deadline'  => 'nullable|date',
            'demurrage_date'      => 'nullable|date',
            'release_date'        => 'nullable|date',
            'container_return_date' => 'nullable|date',
        ], [
            'customer_id.required'         => 'Customer wajib dipilih.',
            'bl_number.required'           => 'B/L No wajib diisi.',
            'product_name.required'        => 'Nama barang wajib diisi.',
            'shipping_line.required'       => 'Pelayaran wajib diisi.',
            'vessel_name.required'         => 'Nama kapal wajib diisi.',
            'voy_number.required'          => 'Voy kapal wajib diisi.',
            'vessel_arrival_date.required' => 'Tgl kapal tiba wajib diisi.',
        ]);

        $this->validateContainerGroups($request, 'import', $importOrder, false);

        DB::beginTransaction();
        try {
            $importOrder->update($request->only([
                'customer_id', 'order_date', 'bl_number', 'product_name',
                'shipping_line', 'vessel_name', 'voy_number', 'vessel_arrival_date',
                'pib_number', 'port', 'do_date', 'detention_deadline',
                'demurrage_date', 'release_date', 'container_return_date',
                'issue', 'status',
            ]));

            $order = $importOrder->order_id ? Order::find($importOrder->order_id) : null;
            if (!$order) {
                $order = Order::create([
                    'order_code' => $importOrder->import_order_number,
                    'type' => 'import',
                    'order_date' => $request->order_date,
                    'status' => $request->status,
                    'created_by' => $importOrder->created_by ?? auth()->id(),
                ]);
                $importOrder->update(['order_id' => $order->id]);
            } else {
                $order->update(['order_date' => $request->order_date, 'status' => $request->status]);
            }
            $this->saveContainers($order->id, 'import', $request);

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
        $availableCombineContainers = $this->availableCombineContainers();
        return view('orders.export.create', compact('customers', 'vendors', 'availableCombineContainers'));
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
            'detention_deadline' => 'nullable|date',
            'pickup_date'        => 'nullable|date',
            'return_date'        => 'nullable|date',
        ], [
            'customer_id.required'   => 'Customer wajib dipilih.',
            'do_number.required'     => 'No DO wajib diisi.',
            'product_name.required'  => 'Nama barang wajib diisi.',
            'shipping_line.required' => 'Pelayaran wajib diisi.',
            'vessel_name.required'   => 'Nama kapal wajib diisi.',
            'voy_number.required'    => 'Voy kapal wajib diisi.',
            'closing_date.required'  => 'Tgl Closing wajib diisi.',
        ]);

        $this->validateContainerGroups($request, 'export', null, true);

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
        $existingGroups = $this->containerGroups($exportOrder->order_id, 'export');
        $availableCombineContainers = $this->availableCombineContainers($exportOrder->order_id, $exportOrder->customer_id);

        return view('orders.export.edit', compact('exportOrder', 'customers', 'vendors', 'existingGroups', 'availableCombineContainers'));
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
            'detention_deadline' => 'nullable|date',
            'pickup_date'        => 'nullable|date',
            'return_date'        => 'nullable|date',
        ], [
            'customer_id.required'   => 'Customer wajib dipilih.',
            'do_number.required'     => 'No DO wajib diisi.',
            'product_name.required'  => 'Nama barang wajib diisi.',
            'shipping_line.required' => 'Pelayaran wajib diisi.',
            'vessel_name.required'   => 'Nama kapal wajib diisi.',
            'voy_number.required'    => 'Voy kapal wajib diisi.',
            'closing_date.required'  => 'Tgl Clossing wajib diisi.',
        ]);

        $this->validateContainerGroups($request, 'export', $exportOrder, false);

        DB::beginTransaction();
        try {
            $exportOrder->update($request->only([
                'customer_id', 'order_date', 'shipping_number', 'do_number',
                'product_name', 'shipping_line', 'vessel_name', 'voy_number',
                'closing_date', 'detention_deadline', 'peb_number', 'depo',
                'pickup_date', 'return_date', 'issue', 'status',
            ]));

            $order = $exportOrder->order_id ? Order::find($exportOrder->order_id) : null;
            if (!$order) {
                $order = Order::create([
                    'order_code' => $exportOrder->export_order_number,
                    'type' => 'export',
                    'order_date' => $request->order_date,
                    'status' => $request->status,
                    'created_by' => $exportOrder->created_by ?? auth()->id(),
                ]);
                $exportOrder->update(['order_id' => $order->id]);
            } else {
                $order->update(['order_date' => $request->order_date, 'status' => $request->status]);
            }
            $this->saveContainers($order->id, 'export', $request);

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
                $importOrder = ImportOrder::where('order_id', $order->id)->first();
                $doDate = $importOrder ? $importOrder->do_date : null;
            } else {
                $exportOrder = ExportOrder::where('order_id', $order->id)->first();
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

    protected function containerGroups($orderId, $orderType): array
    {
        if (!$orderId) return [];

        $containers = OrderContainer::where('order_id', $orderId)
            ->where('order_type', $orderType)
            ->with(['comboContainer', 'combineWithContainer'])
            ->orderBy('container_group')->orderBy('id')->get();
        $result = [];

        foreach ($containers->groupBy('container_group') as $items) {
            $first = $items->first();
            if ($first->container_size === 'LCL') {
                $result[] = [
                    'size' => 'LCL',
                    'quantity' => $items->count(),
                    'lclVendor' => $first->vendor ?? '',
                    'ids' => $items->pluck('id')->values()->all(),
                    'containers' => [],
                ];
                continue;
            }

            $result[] = [
                'size' => $first->container_size,
                'quantity' => $items->count(),
                'lclVendor' => '',
                'containers' => $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'number' => $item->container_number ?? '',
                        'type' => $item->container_type ?? '',
                        'vendor' => $item->vendor ?? '',
                        'combo' => $item->comboContainer?->container_number ?? '',
                        'combine' => $item->combine_with_container_id,
                    ];
                })->values()->all(),
            ];
        }

        return $result;
    }

    protected function availableCombineContainers($currentOrderId = null, $customerId = null): array
    {
        $query = OrderContainer::query()
            ->whereIn('container_size', ["20'", "40'"])
            ->whereIn('container_type', ['GP', 'OT', 'HC', 'RF'])
            ->whereNotNull('vendor')->where('vendor', '!=', '')
            ->whereNotNull('container_number')->where('container_number', '!=', '')
            ->whereHas('order', function ($q) use ($currentOrderId) {
                $q->where('status', 'on going');
                if ($currentOrderId) $q->where('id', '!=', $currentOrderId);
            })
            ->with(['order.importOrder', 'order.exportOrder']);

        $containers = $query->get()->filter(function ($container) use ($customerId) {
            $parent = $container->order;
            $relation = $container->order_type === 'import' ? $parent?->importOrder : $parent?->exportOrder;
            return $relation && (!$customerId || (int) $relation->customer_id !== (int) $customerId);
        });

        return $containers->map(function ($container) {
            return [
                'id' => $container->id,
                'orderId' => $container->order_id,
                'number' => $container->container_number,
                'size' => $container->container_size,
                'type' => $container->container_type,
                'vendor' => $container->vendor,
                'orderNumber' => $container->order_type === 'import'
                    ? ($container->order->importOrder->import_order_number ?? $container->order->order_code ?? null)
                    : ($container->order->exportOrder->export_order_number ?? $container->order->order_code ?? null),
            ];
        })->values()->all();
    }

    protected function validateContainerGroups(Request $request, string $orderType, $parent = null, bool $creating = false): void
    {
        if (!$creating && !array_key_exists('groups', $request->all())) return;

        $rules = [
            'groups' => [$creating ? 'required' : 'present', 'array', 'min:1'],
            'groups.*' => ['required', 'array'],
            'groups.*.size' => ['required', Rule::in(["20'", "40'", 'LCL'])],
            'groups.*.quantity' => ['required', 'integer', 'min:1'],
            'groups.*.ids' => ['sometimes', 'array'],
            'groups.*.ids.*' => ['required', 'integer', 'min:1'],
            'groups.*.containers' => ['sometimes', 'array'],
            'groups.*.containers.*' => ['required', 'array'],
            'groups.*.containers.*.id' => ['nullable', 'integer', 'min:1'],
            'groups.*.containers.*.number' => ['nullable', 'string', 'max:255'],
            'groups.*.containers.*.type' => ['nullable', Rule::in(['GP', 'OT', 'HC', 'RF'])],
            'groups.*.containers.*.vendor' => ['nullable', 'string', 'max:255'],
            'groups.*.containers.*.combo' => ['nullable', 'string', 'max:255'],
            'groups.*.containers.*.combine' => ['nullable', 'integer', 'min:1'],
            'groups.*.containers.*.release_date' => ['nullable', 'date'],
            'groups.*.containers.*.return_date' => ['nullable', 'date'],
            'groups.*.lcl_vendor' => ['nullable', 'string', 'max:255'],
        ];
        Validator::make($request->all(), $rules)->validate();
        $groups = $request->input('groups');
        $errors = [];
        $allowedSizes = ["20'", "40'", 'LCL'];
        $allowedTypes = ['GP', 'OT', 'HC', 'RF'];
        $vendorNames = Vendor::where('status', 'active')->pluck('name')->all();
        $currentOrderId = $parent?->order_id;
        $customerId = $request->input('customer_id', $parent?->customer_id);
        $seenIds = [];

        foreach ($groups as $gi => $group) {
            $prefix = "groups.$gi";
            $size = $group['size'] ?? null;
            $quantity = (int) ($group['quantity'] ?? 0);
            if (!in_array($size, $allowedSizes, true)) $errors["$prefix.size"] = 'Ukuran container tidak valid.';
            if ($quantity < 1) $errors["$prefix.quantity"] = 'Jumlah container wajib minimal 1.';

            if ($size === 'LCL') {
                $vendor = $group['lcl_vendor'] ?? null;
                if (!in_array($vendor, $vendorNames, true)) $errors["$prefix.lcl_vendor"] = 'Vendor LCL tidak aktif.';
                foreach (($group['ids'] ?? []) as $id) {
                    if (isset($seenIds[$id])) $errors["$prefix.ids"] = 'ID container duplikat.';
                    $seenIds[$id] = true;
                    $this->validateOwnedContainerId($id, $currentOrderId, $orderType, $errors, "$prefix.ids");
                }
                if (count($group['ids'] ?? []) > $quantity) $errors["$prefix.ids"] = 'Jumlah ID LCL melebihi quantity.';
                continue;
            }

            $containers = $group['containers'] ?? [];
            if (count($containers) !== $quantity) $errors["$prefix.containers"] = 'Quantity harus sama dengan jumlah container.';
            $numbers = [];
            $nodeTypes = [];
            foreach ($containers as $ci => $data) {
                $cp = "$prefix.containers.$ci";
                $number = trim((string) ($data['number'] ?? ''));
                $type = $data['type'] ?? null;
                $vendor = $data['vendor'] ?? null;
                if ($number === '') $errors["$cp.number"] = 'Nomor container wajib diisi.';
                if (!in_array($type, $allowedTypes, true)) $errors["$cp.type"] = 'Type container tidak valid.';
                if (!in_array($vendor, $vendorNames, true)) $errors["$cp.vendor"] = 'Vendor tidak aktif.';
                if ($number !== '' && isset($numbers[$number])) $errors["$cp.number"] = 'Nomor container harus unik dalam group.';
                $numbers[$number] = $ci;
                $nodeTypes[$number] = $type;
                if (!empty($data['id'])) {
                    $id = (int) $data['id'];
                    if (isset($seenIds[$id])) $errors["$cp.id"] = 'ID container duplikat.';
                    $seenIds[$id] = true;
                    $this->validateOwnedContainerId($id, $currentOrderId, $orderType, $errors, "$cp.id");
                }
                $combine = $data['combine'] ?? null;
                if ($combine !== null && $combine !== '') {
                    $target = OrderContainer::with('order')->find($combine);
                    $targetRelation = $target && $target->order_type === 'import' ? $target->order?->importOrder : $target?->order?->exportOrder;
                    if (!$target || !$targetRelation || $target->order_id == $currentOrderId || $target->container_size !== $size || $target->container_type !== $type || $target->vendor !== $vendor || $target->container_size === 'LCL' || $targetRelation->customer_id == $customerId || $target->order?->status !== 'on going') {
                        $errors["$cp.combine"] = 'Target combine tidak valid.';
                    }
                }
            }

            foreach ($containers as $ci => $data) {
                $combo = trim((string) ($data['combo'] ?? ''));
                $number = trim((string) ($data['number'] ?? ''));
                if ($combo !== '' && ($combo === $number || !isset($nodeTypes[$combo]) || $nodeTypes[$combo] !== ($data['type'] ?? null))) {
                    $errors["$prefix.containers.$ci.combo"] = 'Combo harus memilih container lain dengan tipe sama dalam group ini.';
                }
            }
        }

        if ($errors) throw ValidationException::withMessages($errors);
    }

    protected function validateOwnedContainerId($id, $orderId, $orderType, array &$errors, string $key): void
    {
        if (!$orderId || !OrderContainer::where('id', $id)->where('order_id', $orderId)->where('order_type', $orderType)->exists()) {
            $errors[$key] = 'ID container bukan milik order ini.';
        }
    }

    protected function saveContainers($orderId, $orderType, Request $request): void
    {
        $groups = $request->input('groups');
        if ($groups === null || $groups === []) {
            $containerSize = $request->input('container_size');
            if (!$containerSize) return;
            $groups = [[
                'size' => $containerSize,
                'quantity' => $request->input('container_quantity', 1),
                'lcl_vendor' => $request->input('lcl_vendor'),
                'containers' => $request->input('containers', []),
            ]];
        }

        $keptIds = [];
        foreach (array_values($groups) as $groupIndex => $group) {
            $groupNum = $groupIndex + 1;
            $size = $group['size'] ?? null;
            if ($size === 'LCL') {
                $ids = array_values(array_filter(array_map('intval', $group['ids'] ?? [])));
                foreach ($ids as $id) {
                    $container = OrderContainer::where('id', $id)->where('order_id', $orderId)->where('order_type', $orderType)->firstOrFail();
                    $container->update(['container_group' => $groupNum, 'container_size' => 'LCL', 'container_number' => null, 'container_type' => null, 'vendor' => $group['lcl_vendor'] ?? null, 'combo_with' => null, 'combine_with_container_id' => null]);
                    $keptIds[] = $container->id;
                }
                for ($i = count($ids); $i < (int) ($group['quantity'] ?? 0); $i++) {
                    $container = OrderContainer::create(['order_id' => $orderId, 'order_type' => $orderType, 'container_group' => $groupNum, 'container_size' => 'LCL', 'vendor' => $group['lcl_vendor'] ?? null]);
                    $keptIds[] = $container->id;
                }
                continue;
            }

            $local = [];
            foreach (($group['containers'] ?? []) as $data) {
                $id = !empty($data['id']) ? (int) $data['id'] : null;
                $container = $id ? OrderContainer::where('id', $id)->where('order_id', $orderId)->where('order_type', $orderType)->firstOrFail() : new OrderContainer();
                $container->fill(['order_id' => $orderId, 'order_type' => $orderType, 'container_group' => $groupNum, 'container_size' => $size, 'container_number' => trim($data['number']), 'container_type' => $data['type'], 'vendor' => $data['vendor'], 'combo_with' => null, 'combine_with_container_id' => null]);
                if (array_key_exists('release_date', $data)) $container->release_date = $data['release_date'] ?: null;
                if (array_key_exists('return_date', $data)) $container->return_date = $data['return_date'] ?: null;
                $container->save();
                $keptIds[] = $container->id;
                $local[$container->container_number] = $container;
            }
            foreach (($group['containers'] ?? []) as $data) {
                $container = $local[trim($data['number'])];
                $combo = trim((string) ($data['combo'] ?? ''));
                $combine = $data['combine'] ?? null;
                $container->update(['combo_with' => $combo !== '' && isset($local[$combo]) ? $local[$combo]->id : null, 'combine_with_container_id' => $combine !== null && $combine !== '' ? (int) $combine : null]);
            }
        }

        $query = OrderContainer::where('order_id', $orderId)->where('order_type', $orderType);
        if ($keptIds) $query->whereNotIn('id', $keptIds);
        $query->delete();
    }
}
