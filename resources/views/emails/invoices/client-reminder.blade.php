<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #dc2626; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">
        <h1 style="margin: 0;">Invoice Reminder</h1>
    </div>
    
    <div style="background-color: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none;">
        <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $invoice->client_name }},</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            This is a friendly reminder that your invoice <strong>{{ $invoice->invoice_number }}</strong> 
            is due in <strong>{{ $daysUntilDue }} day(s)</strong>.
        </p>
        
        <div style="background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #dc2626;">
            <h2 style="margin-top: 0; color: #dc2626;">Invoice Details</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Invoice Number:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $invoice->invoice_number }}</td>
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
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Please ensure payment is made by the due date to avoid any late fees or service interruptions.
        </p>
        
        @if($invoice->notes)
        <div style="background-color: #fef3c7; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #f59e0b;">
            <p style="margin: 0; font-size: 14px;"><strong>Note:</strong> {{ $invoice->notes }}</p>
        </div>
        @endif
        
        <p style="font-size: 14px; color: #6b7280; margin-top: 30px;">
            If you have any questions or concerns regarding this invoice, please don't hesitate to contact us.
        </p>
        
        <p style="font-size: 14px; margin-top: 20px;">
            Thank you for your business!
        </p>
    </div>
    
    <div style="background-color: #f3f4f6; padding: 15px; text-align: center; border-radius: 0 0 5px 5px; font-size: 12px; color: #6b7280;">
        <p style="margin: 0;">This is an automated reminder. Please do not reply to this email.</p>
    </div>
</body>
</html>
