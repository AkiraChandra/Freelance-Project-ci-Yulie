<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
        return view('customers.create', compact('generatedCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255|unique:customers,customer_name',
            'type'          => 'required|in:ekspor,impor',
        ], [
            'customer_name.required' => 'Nama customer tidak boleh kosong.',
            'customer_name.unique'   => 'Nama customer sudah terdaftar, gunakan nama lain.',
            'customer_name.max'      => 'Nama customer maksimal 255 karakter.',
            'type.required'          => 'Tipe customer harus dipilih.',
            'type.in'                => 'Tipe customer tidak valid.',
        ]);

        $code = Customer::generateCode();

        Customer::create([
            'customer_code' => $code,
            'customer_name' => $request->customer_name,
            'type'          => $request->type,
            'created_by'    => auth()->id(),
        ]);

        return redirect()->route('customers.index')
            ->with('success', "Customer \"{$request->customer_name}\" berhasil didaftarkan dengan kode {$code}!");
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255|unique:customers,customer_name,' . $customer->id,
            'type'          => 'required|in:ekspor,impor',
        ], [
            'customer_name.required' => 'Nama customer tidak boleh kosong.',
            'customer_name.unique'   => 'Nama customer sudah terdaftar, gunakan nama lain.',
            'customer_name.max'      => 'Nama customer maksimal 255 karakter.',
            'type.required'          => 'Tipe customer harus dipilih.',
            'type.in'                => 'Tipe customer tidak valid.',
        ]);

        $customer->update($request->only('customer_name', 'type'));

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
