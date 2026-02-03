<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Clients;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Only admins and super-admins can view invoices
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action.');
        }

        $query = $request->input('search');
        $type = $request->input('type');
        $status = $request->input('status');
        $recurring = $request->input('recurring');

        $invoices = Invoice::when($query, function ($q) use ($query) {
            $q->where('invoice_number', 'LIKE', "%{$query}%")
              ->orWhere('client_name', 'LIKE', "%{$query}%")
              ->orWhere('title', 'LIKE', "%{$query}%");
        })
        ->when($type, function ($q) use ($type) {
            $q->where('type', $type);
        })
        ->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })
        ->when($recurring !== null, function ($q) use ($recurring) {
            $q->where('is_recurring', $recurring == '1');
        })
        ->with('items')
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admins and super-admins can create invoices
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action.');
        }

        return view('invoices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only admins and super-admins can create invoices
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'type' => 'required|in:invoice,receipt,quote',
            'title' => 'nullable|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string|max:500',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            // Recurring fields
            'is_recurring' => 'nullable|boolean',
            'recurring_frequency' => 'nullable|in:weekly,biweekly,monthly,quarterly,yearly',
            'recurring_start_date' => 'nullable|date',
            'recurring_end_date' => 'nullable|date|after_or_equal:recurring_start_date',
            'notification_days_before' => 'nullable|integer|min:0|max:30',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $taxRate = $validated['tax_rate'] ?? 0;
        $taxAmount = ($subtotal - ($validated['discount'] ?? 0)) * ($taxRate / 100);
        $total = $subtotal - ($validated['discount'] ?? 0) + $taxAmount;

        // If client_id is provided, fetch client details
        if (!empty($validated['client_id'])) {
            $client = Clients::find($validated['client_id']);
            if ($client) {
                $validated['client_name'] = $client->client_name ?? $validated['client_name'];
                $validated['client_email'] = $client->email ?? $validated['client_email'];
                $validated['client_address'] = $client->service_address ?? $validated['client_address'];
            }
        }

        // Generate invoice number based on type
        $invoiceNumber = Invoice::generateInvoiceNumber($validated['type']);
        
        // Handle recurring invoice settings
        $isRecurring = $request->has('is_recurring') && $request->boolean('is_recurring');
        $nextRecurringDate = null;
        
        if ($isRecurring && !empty($validated['recurring_frequency']) && !empty($validated['due_date'])) {
            $dueDate = \Carbon\Carbon::parse($validated['due_date']);
            $frequency = $validated['recurring_frequency'];
            
            switch ($frequency) {
                case 'weekly':
                    $nextRecurringDate = $dueDate->copy()->addWeek();
                    break;
                case 'biweekly':
                    $nextRecurringDate = $dueDate->copy()->addWeeks(2);
                    break;
                case 'monthly':
                    $nextRecurringDate = $dueDate->copy()->addMonth();
                    break;
                case 'quarterly':
                    $nextRecurringDate = $dueDate->copy()->addMonths(3);
                    break;
                case 'yearly':
                    $nextRecurringDate = $dueDate->copy()->addYear();
                    break;
            }
        }
        
        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'type' => $validated['type'],
            'title' => $validated['title'] ?? null,
            'client_id' => $validated['client_id'] ?? null,
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_address' => $validated['client_address'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'terms' => $validated['terms'] ?? null,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount' => $validated['discount'] ?? 0,
            'total' => $total,
            'status' => $validated['status'],
            'created_by' => auth()->id(),
            // Recurring fields
            'is_recurring' => $isRecurring,
            'recurring_frequency' => $isRecurring ? ($validated['recurring_frequency'] ?? null) : null,
            'recurring_start_date' => $isRecurring ? ($validated['recurring_start_date'] ?? $validated['invoice_date']) : null,
            'recurring_end_date' => $isRecurring ? ($validated['recurring_end_date'] ?? null) : null,
            'next_recurring_date' => $nextRecurringDate,
            'notification_days_before' => $isRecurring ? ($validated['notification_days_before'] ?? config('invoices.recurring.notification_days_before', 3)) : null,
        ]);

        // Create invoice items
        foreach ($validated['items'] as $index => $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
                'order' => $index,
            ]);
        }

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', ucfirst($invoice->type) . ' created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Only admins and super-admins can view invoices
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action.');
        }

        $invoice = Invoice::with(['items', 'client', 'creator', 'childInvoices', 'parentInvoice', 'notifications'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Only admins and super-admins can edit invoices
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action.');
        }

        $invoice = Invoice::with('items')->findOrFail($id);
        $clients = Clients::orderBy('client_name')->get();
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Only admins and super-admins can update invoices
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action.');
        }

        $invoice = Invoice::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:invoice,receipt,quote',
            'title' => 'nullable|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string|max:500',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            // Recurring fields
            'is_recurring' => 'nullable|boolean',
            'recurring_frequency' => 'nullable|in:weekly,biweekly,monthly,quarterly,yearly',
            'recurring_start_date' => 'nullable|date',
            'recurring_end_date' => 'nullable|date|after_or_equal:recurring_start_date',
            'notification_days_before' => 'nullable|integer|min:0|max:30',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $taxRate = $validated['tax_rate'] ?? 0;
        $taxAmount = ($subtotal - ($validated['discount'] ?? 0)) * ($taxRate / 100);
        $total = $subtotal - ($validated['discount'] ?? 0) + $taxAmount;

        // If client_id is provided, fetch client details
        if (!empty($validated['client_id'])) {
            $client = Clients::find($validated['client_id']);
            if ($client) {
                $validated['client_name'] = $client->client_name ?? $validated['client_name'];
                $validated['client_email'] = $client->email ?? $validated['client_email'];
                $validated['client_address'] = $client->service_address ?? $validated['client_address'];
            }
        }

        // Handle recurring invoice settings
        $isRecurring = $request->has('is_recurring') && $request->boolean('is_recurring');
        $nextRecurringDate = $invoice->next_recurring_date;
        
        if ($isRecurring && !empty($validated['recurring_frequency']) && !empty($validated['due_date'])) {
            $dueDate = \Carbon\Carbon::parse($validated['due_date']);
            $frequency = $validated['recurring_frequency'];
            
            // Only update next_recurring_date if it's not already set or if due date changed
            if (!$nextRecurringDate || $invoice->due_date != $validated['due_date']) {
                switch ($frequency) {
                    case 'weekly':
                        $nextRecurringDate = $dueDate->copy()->addWeek();
                        break;
                    case 'biweekly':
                        $nextRecurringDate = $dueDate->copy()->addWeeks(2);
                        break;
                    case 'monthly':
                        $nextRecurringDate = $dueDate->copy()->addMonth();
                        break;
                    case 'quarterly':
                        $nextRecurringDate = $dueDate->copy()->addMonths(3);
                        break;
                    case 'yearly':
                        $nextRecurringDate = $dueDate->copy()->addYear();
                        break;
                }
            }
        } elseif (!$isRecurring) {
            $nextRecurringDate = null;
        }

        $invoice->update([
            'type' => $validated['type'],
            'title' => $validated['title'] ?? null,
            'client_id' => $validated['client_id'] ?? null,
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_address' => $validated['client_address'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'terms' => $validated['terms'] ?? null,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount' => $validated['discount'] ?? 0,
            'total' => $total,
            'status' => $validated['status'],
            // Recurring fields
            'is_recurring' => $isRecurring,
            'recurring_frequency' => $isRecurring ? ($validated['recurring_frequency'] ?? $invoice->recurring_frequency) : null,
            'recurring_start_date' => $isRecurring ? ($validated['recurring_start_date'] ?? $invoice->recurring_start_date ?? $validated['invoice_date']) : null,
            'recurring_end_date' => $isRecurring ? ($validated['recurring_end_date'] ?? null) : null,
            'next_recurring_date' => $nextRecurringDate,
            'notification_days_before' => $isRecurring ? ($validated['notification_days_before'] ?? $invoice->notification_days_before ?? config('invoices.recurring.notification_days_before', 3)) : null,
        ]);

        // Delete existing items and create new ones
        $invoice->items()->delete();
        foreach ($validated['items'] as $index => $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
                'order' => $index,
            ]);
        }

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', ucfirst($invoice->type) . ' updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Only super-admins can delete invoices
        if (!auth()->user() || !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action. Only super-admins can delete invoices.');
        }

        $invoice = Invoice::findOrFail($id);
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', ucfirst($invoice->type) . ' deleted successfully!');
    }

    /**
     * Duplicate an invoice
     */
    public function duplicate($id)
    {
        // Only admins and super-admins can duplicate invoices
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action.');
        }

        $originalInvoice = Invoice::with('items')->findOrFail($id);

        $newInvoice = $originalInvoice->replicate();
        $newInvoice->invoice_number = null; // Clear invoice number to generate a new one
        $newInvoice->status = 'draft';
        $newInvoice->created_by = auth()->id();
        $newInvoice->save();

        // Duplicate items
        foreach ($originalInvoice->items as $item) {
            $newItem = $item->replicate();
            $newItem->invoice_id = $newInvoice->id;
            $newItem->save();
        }

        return redirect()->route('invoices.edit', $newInvoice->id)
            ->with('success', ucfirst($newInvoice->type) . ' duplicated successfully!');
    }

    /**
     * Generate PDF for invoice
     */
    public function pdf($id)
    {
        // Only admins and super-admins can download PDFs
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action.');
        }

        $invoice = Invoice::with(['items', 'client', 'creator'])->findOrFail($id);

        // Prepare logo data for PDF
        $logoPath = public_path('images/logo.png');
        $logoData = null;
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
        }

        $html = view('invoices.pdf', compact('invoice', 'logoData'))->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = $invoice->invoice_number . '.pdf';

        return $dompdf->stream($filename);
    }

    /**
     * Toggle recurring invoice pause/resume
     */
    public function toggleRecurring($id)
    {
        // Only admins and super-admins can toggle recurring
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action.');
        }

        $invoice = Invoice::findOrFail($id);

        if (!$invoice->is_recurring) {
            return redirect()->route('invoices.show', $invoice->id)
                ->with('error', 'This invoice is not a recurring invoice.');
        }

        $invoice->update([
            'is_recurring_paused' => !$invoice->is_recurring_paused,
        ]);

        $status = $invoice->is_recurring_paused ? 'paused' : 'resumed';
        
        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', "Recurring invoice has been {$status} successfully.");
    }

    /**
     * Manually generate next invoice for recurring invoice
     */
    public function generateNext($id)
    {
        // Only admins and super-admins can generate next invoice
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized action.');
        }

        $invoice = Invoice::findOrFail($id);

        if (!$invoice->is_recurring) {
            return redirect()->route('invoices.show', $invoice->id)
                ->with('error', 'This invoice is not a recurring invoice.');
        }

        if ($invoice->parent_invoice_id) {
            return redirect()->route('invoices.show', $invoice->id)
                ->with('error', 'Cannot generate next invoice from a child invoice. Please use the parent invoice.');
        }

        $notificationService = app(\App\Services\RecurringInvoiceNotificationService::class);
        $newInvoice = $notificationService->generateNextInvoice($invoice);

        if ($newInvoice) {
            return redirect()->route('invoices.show', $newInvoice->id)
                ->with('success', 'Next invoice generated successfully!');
        }

        return redirect()->route('invoices.show', $invoice->id)
            ->with('error', 'Failed to generate next invoice. Please check if the invoice can be generated.');
    }
}
