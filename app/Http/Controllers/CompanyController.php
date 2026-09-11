<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('company_code')->get();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'pic_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $lastCompany = Company::orderBy('id', 'desc')->first();
        $nextCode = ($lastCompany ? (int)$lastCompany->company_code : 0) + 1;
        $companyCode = str_pad($nextCode, 6, '0', STR_PAD_LEFT);

        Company::create([
            'company_code' => $companyCode,
            'company_name' => $request->company_name,
            'pic_name' => $request->pic_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('companies.index')->with('success', "Perusahaan {$request->company_name} berhasil ditambahkan!");
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'pic_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $company->update($request->only('company_name', 'pic_name', 'phone', 'email', 'address'));

        return redirect()->route('companies.index')->with('success', "Perusahaan berhasil diupdate!");
    }

    public function destroy(Company $company)
    {
        $companyName = $company->company_name;
        $company->delete();

        return redirect()->route('companies.index')->with('success', "Perusahaan {$companyName} berhasil dihapus!");
    }
}
