<?php

namespace App\Http\Controllers;

use App\Models\ExportOrder;
use App\Models\ImportOrder;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * List all invoices.
     */
    public function index()
    {
        $invoices = Invoice::with('creator')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($invoice) {
                $invoice->order; // trigger accessor
                return $invoice;
            });

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show create form — optionally pre-select order.
     */
    public function create(Request $request)
    {
        // Get IDs of orders that already have invoices
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

    /**
     * Store a new invoice.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_type'              => 'required|in:import,export',
            'order_id'                => 'required|integer',
            'invoice_title'           => 'required|string|max:255',
            'vessel_name'             => 'nullable|string|max:255',
            'vessel_date'             => 'nullable|string|max:255',
            'destination'             => 'nullable|string|max:255',
            'party_display'           => 'nullable|string|max:255',
            'product_name'            => 'nullable|string|max:255',
            'tonage'                  => 'nullable|string|max:255',
            'merk'                    => 'nullable|string|max:255',
            'container_display'       => 'nullable|string|max:255',
            'sections'                => 'required|array|min:1',
            'sections.*.name'         => 'required|string|max:255',
            'sections.*.items'        => 'required|array|min:1',
            'sections.*.items.*.label'  => 'required|string|max:255',
            'sections.*.items.*.amount' => 'required|numeric|min:0',
            'panjar'                  => 'nullable|numeric|min:0',
            'include_tax'             => 'nullable|boolean',
            'tax_percentage'          => 'nullable|numeric|min:0|max:100',
        ]);

        // Verify order exists
        if ($request->order_type === 'import') {
            $exists = ImportOrder::where('id', $request->order_id)->exists();
        } else {
            $exists = ExportOrder::where('id', $request->order_id)->exists();
        }

        if (!$exists) {
            return back()->withErrors(['order_id' => 'Order tidak ditemukan.'])->withInput();
        }

        // Generate nota number
        $nota = Invoice::generateNotaNumber($request->order_type, $request->order_id);

        $invoice = Invoice::create([
            'order_type'        => $request->order_type,
            'order_id'          => $request->order_id,
            'nota_number'       => $nota['nota'],
            'invoice_title'     => $request->invoice_title,
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
            'created_by'        => auth()->id(),
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat!');
    }

    /**
     * Show invoice detail.
     */
    public function show(Invoice $invoice)
    {
        $invoice->order; // trigger accessor
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Edit form — only allows editing existing row values (label/amount), not add/remove.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->order; // trigger accessor
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update invoice — only update existing rows (labels, amounts), header fields, tax, panjar.
     * Cannot change order, cannot add/remove items.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_title'           => 'required|string|max:255',
            'vessel_name'             => 'nullable|string|max:255',
            'vessel_date'             => 'nullable|string|max:255',
            'destination'             => 'nullable|string|max:255',
            'party_display'           => 'nullable|string|max:255',
            'product_name'            => 'nullable|string|max:255',
            'tonage'                  => 'nullable|string|max:255',
            'merk'                    => 'nullable|string|max:255',
            'container_display'       => 'nullable|string|max:255',
            'sections'                => 'required|array|min:1',
            'sections.*.name'         => 'required|string|max:255',
            'sections.*.items'        => 'required|array|min:1',
            'sections.*.items.*.label'  => 'required|string|max:255',
            'sections.*.items.*.amount' => 'required|numeric|min:0',
            'panjar'                  => 'nullable|numeric|min:0',
            'include_tax'             => 'nullable|boolean',
            'tax_percentage'          => 'nullable|numeric|min:0|max:100',
        ]);

        $invoice->update([
            'invoice_title'     => $request->invoice_title,
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
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil diupdate!');
    }

    /**
     * Create a revision invoice (susulan) — uses edit view with full add/remove capability.
     */
    public function createRevision(Invoice $invoice)
    {
        $invoice->order; // trigger accessor

        return view('invoices.edit', [
            'invoice'    => $invoice,
            'isRevision' => true,
        ]);
    }

    /**
     * Store a revision invoice as a new record with a new nota number.
     */
    public function storeRevision(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_title'           => 'required|string|max:255',
            'vessel_name'             => 'nullable|string|max:255',
            'vessel_date'             => 'nullable|string|max:255',
            'destination'             => 'nullable|string|max:255',
            'party_display'           => 'nullable|string|max:255',
            'product_name'            => 'nullable|string|max:255',
            'tonage'                  => 'nullable|string|max:255',
            'merk'                    => 'nullable|string|max:255',
            'container_display'       => 'nullable|string|max:255',
            'sections'                => 'required|array|min:1',
            'sections.*.name'         => 'required|string|max:255',
            'sections.*.items'        => 'required|array|min:1',
            'sections.*.items.*.label'  => 'required|string|max:255',
            'sections.*.items.*.amount' => 'required|numeric|min:0',
            'panjar'                  => 'nullable|numeric|min:0',
            'include_tax'             => 'nullable|boolean',
            'tax_percentage'          => 'nullable|numeric|min:0|max:100',
        ]);

        // Generate new nota number for same order
        $nota = Invoice::generateNotaNumber($invoice->order_type, $invoice->order_id);

        $newInvoice = Invoice::create([
            'order_type'        => $invoice->order_type,
            'order_id'          => $invoice->order_id,
            'nota_number'       => $nota['nota'],
            'invoice_title'     => $request->invoice_title,
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
            'created_by'        => auth()->id(),
        ]);

        return redirect()->route('invoices.show', $newInvoice)
            ->with('success', 'Invoice revisi (susulan) berhasil dibuat! Nota: ' . $nota['nota']);
    }

    /**
     * Delete invoice.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus!');
    }

    /**
     * Generate PDF for specific sections.
     */
    public function generatePdf(Request $request, Invoice $invoice)
    {
        $invoice->order; // trigger accessor

        // Which sections to print (default: all)
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
