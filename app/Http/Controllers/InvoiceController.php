<?php

namespace App\Http\Controllers;

use App\Models\ExportOrder;
use App\Models\ImportOrder;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('creator')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($invoice) {
                $invoice->order;
                return $invoice;
            });

        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $usedImportIds = Invoice::where('order_type', 'import')->pluck('order_id')->toArray();
        $usedExportIds = Invoice::where('order_type', 'export')->pluck('order_id')->toArray();

        $importOrders = ImportOrder::with('customer')
            ->whereNotIn('id', $usedImportIds)
            ->orderBy('import_order_number')->get();
        $exportOrders = ExportOrder::with('customer')
            ->whereNotIn('id', $usedExportIds)
            ->orderBy('export_order_number')->get();

        $selectedOrderType = $request->query('order_type');
        $selectedOrderId = $request->query('order_id');

        return view('invoices.create', compact('importOrders', 'exportOrders', 'selectedOrderType', 'selectedOrderId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_type'              => 'required|in:import,export',
            'order_id'                => 'required|integer',
            'invoice_title'           => 'required|string|max:255',
            'invoice_type'            => 'required|in:reimbursement,invoice',
            'vessel_name'             => 'nullable|string|max:255',
            'vessel_date'             => 'nullable|string|max:255',
            'destination'             => 'nullable|string|max:255',
            'party_display'           => 'nullable|string|max:255',
            'product_name'            => 'nullable|string|max:255',
            'tonage'                  => 'nullable|string|max:255',
            'merk'                    => 'nullable|string|max:255',
            'container_display'       => 'nullable|string|max:255',
            'sections'                => 'required|array|min:3|max:3',
            'sections.*.name'         => 'required|string|max:255',
            'sections.*.items'        => 'required|array|min:1',
            'sections.*.items.*.label'  => 'required|string|max:255',
            'sections.*.items.*.amount' => 'required|numeric|min:0',
            'panjar'                  => 'nullable|numeric|min:0',
            'include_tax'             => 'nullable|boolean',
            'tax_percentage'          => 'nullable|numeric|min:0|max:100',
            'taxable_sections'        => 'nullable|array',
            'taxable_sections.*'      => 'integer|min:0|max:2',
        ]);

        if ($request->order_type === 'import') {
            $exists = ImportOrder::where('id', $request->order_id)->exists();
        } else {
            $exists = ExportOrder::where('id', $request->order_id)->exists();
        }

        if (!$exists) {
            return back()->withErrors(['order_id' => 'Order tidak ditemukan.'])->withInput();
        }

        $nota = Invoice::generateNotaNumber($request->order_type, $request->order_id);

        $invoice = Invoice::create([
            'order_type'        => $request->order_type,
            'order_id'          => $request->order_id,
            'nota_number'       => $nota['nota'],
            'invoice_title'     => $request->invoice_title,
            'invoice_type'      => $request->invoice_type,
            'vessel_name'       => $request->vessel_name,
            'vessel_date'       => $request->vessel_date,
            'destination'       => $request->destination,
            'party_display'     => $request->party_display,
            'product_name'      => $request->product_name,
            'tonage'            => $request->tonage,
            'merk'              => $request->merk,
            'container_display' => $request->container_display,
            'revision'          => $nota['revision'],
            'sections'          => $request->sections,
            'panjar'            => $request->panjar ?? 0,
            'include_tax'       => $request->boolean('include_tax'),
            'tax_percentage'    => $request->tax_percentage ?? 1.1,
            'taxable_sections'  => $request->taxable_sections ?? [],
            'created_by'        => auth()->id(),
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->order;
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->order;
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_title'           => 'required|string|max:255',
            'invoice_type'            => 'required|in:reimbursement,invoice',
            'vessel_name'             => 'nullable|string|max:255',
            'vessel_date'             => 'nullable|string|max:255',
            'destination'             => 'nullable|string|max:255',
            'party_display'           => 'nullable|string|max:255',
            'product_name'            => 'nullable|string|max:255',
            'tonage'                  => 'nullable|string|max:255',
            'merk'                    => 'nullable|string|max:255',
            'container_display'       => 'nullable|string|max:255',
            'sections'                => 'required|array|min:3|max:3',
            'sections.*.name'         => 'required|string|max:255',
            'sections.*.items'        => 'required|array|min:1',
            'sections.*.items.*.label'  => 'required|string|max:255',
            'sections.*.items.*.amount' => 'required|numeric|min:0',
            'panjar'                  => 'nullable|numeric|min:0',
            'include_tax'             => 'nullable|boolean',
            'tax_percentage'          => 'nullable|numeric|min:0|max:100',
            'taxable_sections'        => 'nullable|array',
            'taxable_sections.*'      => 'integer|min:0|max:2',
        ]);

        $invoice->update([
            'invoice_title'     => $request->invoice_title,
            'invoice_type'      => $request->invoice_type,
            'vessel_name'       => $request->vessel_name,
            'vessel_date'       => $request->vessel_date,
            'destination'       => $request->destination,
            'party_display'     => $request->party_display,
            'product_name'      => $request->product_name,
            'tonage'            => $request->tonage,
            'merk'              => $request->merk,
            'container_display' => $request->container_display,
            'sections'          => $request->sections,
            'panjar'            => $request->panjar ?? 0,
            'include_tax'       => $request->boolean('include_tax'),
            'tax_percentage'    => $request->tax_percentage ?? 1.1,
            'taxable_sections'  => $request->taxable_sections ?? [],
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil diupdate!');
    }

    public function createRevision(Invoice $invoice)
    {
        $invoice->order;

        return view('invoices.edit', [
            'invoice'    => $invoice,
            'isRevision' => true,
        ]);
    }

    public function storeRevision(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_title'           => 'required|string|max:255',
            'invoice_type'            => 'required|in:reimbursement,invoice',
            'vessel_name'             => 'nullable|string|max:255',
            'vessel_date'             => 'nullable|string|max:255',
            'destination'             => 'nullable|string|max:255',
            'party_display'           => 'nullable|string|max:255',
            'product_name'            => 'nullable|string|max:255',
            'tonage'                  => 'nullable|string|max:255',
            'merk'                    => 'nullable|string|max:255',
            'container_display'       => 'nullable|string|max:255',
            'sections'                => 'required|array|min:3|max:3',
            'sections.*.name'         => 'required|string|max:255',
            'sections.*.items'        => 'required|array|min:1',
            'sections.*.items.*.label'  => 'required|string|max:255',
            'sections.*.items.*.amount' => 'required|numeric|min:0',
            'panjar'                  => 'nullable|numeric|min:0',
            'include_tax'             => 'nullable|boolean',
            'tax_percentage'          => 'nullable|numeric|min:0|max:100',
            'taxable_sections'        => 'nullable|array',
            'taxable_sections.*'      => 'integer|min:0|max:2',
        ]);

        $nota = Invoice::generateNotaNumber($invoice->order_type, $invoice->order_id);

        $newInvoice = Invoice::create([
            'order_type'        => $invoice->order_type,
            'order_id'          => $invoice->order_id,
            'nota_number'       => $nota['nota'],
            'invoice_title'     => $request->invoice_title,
            'invoice_type'      => $request->invoice_type,
            'vessel_name'       => $request->vessel_name,
            'vessel_date'       => $request->vessel_date,
            'destination'       => $request->destination,
            'party_display'     => $request->party_display,
            'product_name'      => $request->product_name,
            'tonage'            => $request->tonage,
            'merk'              => $request->merk,
            'container_display' => $request->container_display,
            'revision'          => $nota['revision'],
            'sections'          => $request->sections,
            'panjar'            => $request->panjar ?? 0,
            'include_tax'       => $request->boolean('include_tax'),
            'tax_percentage'    => $request->tax_percentage ?? 1.1,
            'taxable_sections'  => $request->taxable_sections ?? [],
            'created_by'        => auth()->id(),
        ]);

        return redirect()->route('invoices.show', $newInvoice)
            ->with('success', 'Invoice revisi (susulan) berhasil dibuat! Nota: ' . $nota['nota']);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus!');
    }

    public function generatePdf(Request $request, Invoice $invoice)
    {
        $invoice->order;

        $sectionIndices = $request->query('sections');
        if ($sectionIndices !== null) {
            $sectionIndices = array_map('intval', explode(',', $sectionIndices));
        }

        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice'        => $invoice,
            'sectionIndices' => $sectionIndices,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $safeFilename = str_replace(['/', '\\'], '-', $invoice->nota_number ?? 'invoice');

        return $pdf->stream("Invoice-{$safeFilename}.pdf");
    }
}
