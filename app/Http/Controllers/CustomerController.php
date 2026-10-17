<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Support\IndonesiaCities;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('customer_code')->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $generatedCode = Customer::generateCode();
        $cityOptions = $this->cityOptions();

        return view('customers.create', compact('generatedCode', 'cityOptions'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'city' => mb_strtoupper(trim($request->input('city', 'MEDAN'))) ?: 'MEDAN',
        ]);

        $request->validate([
            'customer_name' => 'required|string|max:255|unique:customers,customer_name',
            'city'          => 'required|string|max:100',
        ], [
            'customer_name.required' => 'Nama customer tidak boleh kosong.',
            'customer_name.unique'   => 'Nama customer sudah terdaftar, gunakan nama lain.',
            'customer_name.max'      => 'Nama customer maksimal 255 karakter.',
            'city.required'          => 'Kota operasional wajib diisi atau dipilih.',
        ]);

        $code = Customer::generateCode();

        Customer::create([
            'customer_code' => $code,
            'customer_name' => $request->customer_name,
            'city'          => $request->city,
            'created_by'    => auth()->id(),
        ]);

        return redirect()->route('customers.index')
            ->with('success', "Customer \"{$request->customer_name}\" berhasil didaftarkan dengan kode {$code}!");
    }

    public function edit(Customer $customer)
    {
        $cityOptions = $this->cityOptions();

        return view('customers.edit', compact('customer', 'cityOptions'));
    }

    private function cityOptions(): array
    {
        // Sertakan nilai lama (misalnya kota luar negeri) tanpa mengubah data customer yang sudah ada.
        $names = array_merge(
            IndonesiaCities::names(),
            Customer::whereNotNull('city')->distinct()->pluck('city')->all(),
            [old('city', 'MEDAN')],
        );
        $names = array_values(array_unique(array_filter(array_map(
            fn ($name) => mb_strtoupper(trim((string) $name)),
            $names,
        ))));
        sort($names, SORT_STRING);
        $names = array_values(array_diff($names, ['MEDAN']));
        array_unshift($names, 'MEDAN');

        return array_map(fn ($name) => ['value' => $name, 'label' => $name], $names);
    }

    public function update(Request $request, Customer $customer)
    {
        $request->merge([
            'city' => mb_strtoupper(trim($request->input('city', 'MEDAN'))) ?: 'MEDAN',
        ]);

        $request->validate([
            'customer_name' => 'required|string|max:255|unique:customers,customer_name,' . $customer->id,
            'city'          => 'required|string|max:100',
        ], [
            'customer_name.required' => 'Nama customer tidak boleh kosong.',
            'customer_name.unique'   => 'Nama customer sudah terdaftar, gunakan nama lain.',
            'customer_name.max'      => 'Nama customer maksimal 255 karakter.',
            'city.required'          => 'Kota operasional wajib diisi atau dipilih.',
        ]);

        $customer->update($request->only('customer_name', 'city'));

        return redirect()->route('customers.index')
            ->with('success', "Customer \"{$customer->customer_name}\" berhasil diupdate!");
    }

    public function destroy(Customer $customer)
    {
        $name = $customer->customer_name;
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', "Customer \"{$name}\" berhasil dihapus!");
    }
}
