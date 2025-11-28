<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }} - {{ ucfirst($invoice->type) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #dc2626;
        }
        .company-logo {
            margin-bottom: 15px;
        }
        .company-logo img {
            max-height: 80px;
            max-width: 200px;
        }
        .company-info h1 {
            color: #dc2626;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .company-info p {
            color: #666;
            margin: 3px 0;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        .invoice-info p {
            color: #666;
            margin: 3px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 10px;
        }
        .status-draft { background: #f3f4f6; color: #374151; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
        .status-cancelled { background: #fef3c7; color: #92400e; }
        .client-section {
            margin-bottom: 30px;
        }
        .client-section h3 {
            color: #dc2626;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .client-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 4px;
        }
        .client-box p {
            margin: 5px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table thead {
            background: #dc2626;
            color: white;
        }
        .items-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .items-table th.text-right {
            text-align: right;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table td.text-right {
            text-align: right;
        }
        .items-table tbody tr:hover {
            background: #f9fafb;
        }
        .totals {
            margin-left: auto;
            width: 300px;
            margin-bottom: 30px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        .totals-row.total {
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        .totals-row.total .amount {
            color: #dc2626;
            font-size: 18px;
        }
        .notes-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .notes-section h3 {
            color: #dc2626;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .notes-section p {
            color: #666;
            white-space: pre-line;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #999;
            font-size: 10px;
        }
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                @if(isset($logoData) && $logoData)
                <div class="company-logo">
                    <img src="data:image/png;base64,{{ $logoData }}" alt="Raslordeck Limited Logo">
                </div>
                @endif
                <h1>Raslordeck Limited</h1>
                <p>No 10 Ada George road, Port Harcourt.</p>
                <p>Email: info@raslordeckltd.com</p>
                <p>Phone: +2349131271958</p>
            </div>
            <div class="invoice-info">
                <h2>{{ $invoice->title ?? ucfirst($invoice->type) }}</h2>
                <p><strong>{{ ucfirst($invoice->type) }} #:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
                @if($invoice->due_date)
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                @endif
                <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
            </div>
        </div>

        <!-- Client Information -->
        <div class="client-section">
            <h3>Bill To:</h3>
            <div class="client-box">
                <p><strong>{{ $invoice->client_name ?? 'N/A' }}</strong></p>
                @if($invoice->client_email)
                <p>{{ $invoice->client_email }}</p>
                @endif
                @if($invoice->client_address)
                <p>{{ $invoice->client_address }}</p>
                @endif
            </div>
        </div>

        <!-- Invoice Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                    <td class="text-right">₦{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">₦{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <span>Subtotal:</span>
                <span class="amount">₦{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if($invoice->discount > 0)
            <div class="totals-row">
                <span>Discount:</span>
                <span class="amount">-₦{{ number_format($invoice->discount, 2) }}</span>
            </div>
            @endif
            @if($invoice->tax_rate > 0)
            <div class="totals-row">
                <span>Tax ({{ $invoice->tax_rate }}%):</span>
                <span class="amount">₦{{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            @endif
            <div class="totals-row total">
                <span>Total:</span>
                <span class="amount">₦{{ number_format($invoice->total, 2) }}</span>
            </div>
        </div>

        <!-- Notes and Terms -->
        @if($invoice->notes || $invoice->terms)
        <div class="notes-section">
            @if($invoice->notes)
            <div style="margin-bottom: 20px;">
                <h3>Notes</h3>
                <p>{{ $invoice->notes }}</p>
            </div>
            @endif
            @if($invoice->terms)
            <div>
                <h3>Terms & Conditions</h3>
                <p>{{ $invoice->terms }}</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Generated on {{ now()->format('M d, Y h:i A') }} | Created by: {{ $invoice->creator->name ?? 'N/A' }}</p>
        </div>
    </div>
</body>
</html>


