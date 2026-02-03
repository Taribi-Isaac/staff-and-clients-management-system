<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Invoice Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #dc2626; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">
        <h1 style="margin: 0;">Admin Reminder: Invoice Follow-up Required</h1>
    </div>
    
    <div style="background-color: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello Admin Team,</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            This is a reminder that invoice <strong>{{ $invoice->invoice_number }}</strong> 
            is due in <strong>{{ $daysUntilDue }} day(s)</strong> and may require follow-up.
        </p>
        
        <div style="background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #dc2626;">
            <h2 style="margin-top: 0; color: #dc2626;">Invoice Details</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Invoice Number:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Client Name:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $invoice->client_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Client Email:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $invoice->client_email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Invoice Date:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $invoice->invoice_date->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Due Date:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; color: #dc2626; font-weight: bold;">
                        {{ $invoice->due_date ? $invoice->due_date->format('F d, Y') : 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Status:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="text-transform: capitalize; font-weight: bold;">{{ $invoice->status }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Total Amount:</strong></td>
                    <td style="padding: 8px 0; font-size: 18px; font-weight: bold; color: #dc2626;">
                        ₦{{ number_format($invoice->total, 2) }}
                    </td>
                </tr>
            </table>
        </div>
        
        @if($invoice->title)
        <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px;">
            <strong>Description:</strong> {{ $invoice->title }}
        </p>
        @endif
        
        <div style="background-color: #fef3c7; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #f59e0b;">
            <p style="margin: 0; font-size: 14px;">
                <strong>Action Required:</strong> Please follow up with the client to ensure payment is received by the due date.
            </p>
        </div>
        
        @if($invoice->is_recurring)
        <div style="background-color: #dbeafe; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #3b82f6;">
            <p style="margin: 0; font-size: 14px;">
                <strong>Recurring Invoice:</strong> This is a recurring invoice. 
                @if($invoice->next_recurring_date)
                    Next invoice scheduled for: {{ $invoice->next_recurring_date->format('F d, Y') }}
                @endif
            </p>
        </div>
        @endif
        
        <p style="font-size: 14px; color: #6b7280; margin-top: 30px;">
            View invoice details in the system for more information.
        </p>
    </div>
    
    <div style="background-color: #f3f4f6; padding: 15px; text-align: center; border-radius: 0 0 5px 5px; font-size: 12px; color: #6b7280;">
        <p style="margin: 0;">This is an automated reminder from the invoice management system.</p>
    </div>
</body>
</html>
