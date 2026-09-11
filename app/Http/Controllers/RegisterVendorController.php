<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorPrice;
use Illuminate\Http\Request;

class RegisterVendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('prices')->orderBy('created_at', 'desc')->paginate(10);
        return view('vendor.register', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:vendors,name',
            'status' => 'required|in:active,inactive',
            'prices' => 'required|array|min:1',
            'prices.*.lokasi' => 'required|string',
            'prices.*.price_20' => 'required|numeric|min:0',
            'prices.*.price_40' => 'required|numeric|min:0',
            'prices.*.price_2x20' => 'required|numeric|min:0',
            'prices.*.status' => 'required|in:active,inactive',
        ]);

        try {
            $vendor = Vendor::create([
                'name' => $validated['name'],
                'status' => $validated['status'],
            ]);

            foreach ($validated['prices'] as $price) {
                VendorPrice::create([
                    'vendor_id' => $vendor->id,
                    'lokasi' => $price['lokasi'],
                    'price_20' => $price['price_20'],
                    'price_40' => $price['price_40'],
                    'price_2x20' => $price['price_2x20'],
                    'status' => $price['status'],
                ]);
            }

            return redirect()->route('vendor.register')->with('success', 'Vendor berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan vendor: ' . $e->getMessage());
        }
    }

    public function show(Vendor $vendor)
    {
        $vendor->load('prices');
        return view('vendor.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        $vendor->load('prices');
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:vendors,name,' . $vendor->id,
            'status' => 'required|in:active,inactive',
            'prices' => 'required|array|min:1',
            'prices.*.id' => 'nullable|exists:vendor_prices,id',
            'prices.*.lokasi' => 'required|string',
            'prices.*.price_20' => 'required|numeric|min:0',
            'prices.*.price_40' => 'required|numeric|min:0',
            'prices.*.price_2x20' => 'required|numeric|min:0',
            'prices.*.status' => 'required|in:active,inactive',
        ]);

        try {
            $vendor->update([
                'name' => $validated['name'],
                'status' => $validated['status'],
            ]);

            $priceIds = [];
            foreach ($validated['prices'] as $price) {
                if (isset($price['id']) && $price['id']) {
                    VendorPrice::find($price['id'])->update([
                        'lokasi' => $price['lokasi'],
                        'price_20' => $price['price_20'],
                        'price_40' => $price['price_40'],
                        'price_2x20' => $price['price_2x20'],
                        'status' => $price['status'],
                    ]);
                    $priceIds[] = $price['id'];
                } else {
                    $createdPrice = VendorPrice::create([
                        'vendor_id' => $vendor->id,
                        'lokasi' => $price['lokasi'],
                        'price_20' => $price['price_20'],
                        'price_40' => $price['price_40'],
                        'price_2x20' => $price['price_2x20'],
                        'status' => $price['status'],
                    ]);
                    $priceIds[] = $createdPrice->id;
                }
            }

            VendorPrice::where('vendor_id', $vendor->id)
                ->whereNotIn('id', $priceIds)
                ->delete();

            return redirect()->route('vendor.register')->with('success', 'Vendor berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui vendor: ' . $e->getMessage());
        }
    }

    public function destroy(Vendor $vendor)
    {
        try {
            $vendor->delete();
            return redirect()->route('vendor.register')->with('success', 'Vendor berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus vendor: ' . $e->getMessage());
        }
    }

    public function destroyPrice(VendorPrice $vendorPrice)
    {
        try {
            $vendor = $vendorPrice->vendor;
            $vendorPrice->delete();
            return back()->with('success', 'Harga vendor berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus harga vendor: ' . $e->getMessage());
        }
    }
}
