# Recurring Invoices Implementation Plan

## Overview
This document outlines the implementation plan for adding recurring invoice functionality with email notifications to the invoice management system.

## Requirements Analysis

### Core Features
1. **Mark Invoice as Recurring**
   - Add checkbox/option to mark invoice as recurring during creation/editing
   - Store recurrence settings (frequency, next due date, etc.)

2. **Email Notifications**
   - Client notification: Send reminder X days before due date
   - Admin notification: Send reminder to invoices@raslordeckltd.com and info@raslordeckltd.com
   - Configurable notification days before due date

3. **Additional Features**
   - Auto-generate next invoice when current one is paid
   - Track notification history
   - Pause/resume recurring invoices
   - View all recurring invoices
   - Edit recurring settings

## Database Schema Changes

### 1. Migration: Add Recurring Fields to Invoices Table
```php
- is_recurring (boolean, default: false)
- recurring_frequency (enum: 'monthly', 'quarterly', 'yearly', 'weekly', 'biweekly')
- recurring_start_date (date, nullable)
- recurring_end_date (date, nullable)
- next_recurring_date (date, nullable)
- parent_invoice_id (foreign key, nullable) - links to original recurring invoice
- notification_days_before (integer, default: 3) - days before due date to send notification
- last_notification_sent_at (datetime, nullable)
- is_recurring_paused (boolean, default: false)
```

### 2. Migration: Create Recurring Invoice Notifications Table
```php
- id
- invoice_id (foreign key)
- notification_type (enum: 'client_reminder', 'admin_reminder')
- sent_to (string) - email address
- sent_at (datetime)
- scheduled_for_date (date) - when notification was scheduled for
- status (enum: 'pending', 'sent', 'failed')
- error_message (text, nullable)
- timestamps
```

## Implementation Steps

### Phase 1: Database & Model Updates

1. **Create Migration for Recurring Fields**
   - Add all recurring-related columns to invoices table
   - Create recurring_invoice_notifications table

2. **Update Invoice Model**
   - Add fillable fields for recurring attributes
   - Add casts for dates and booleans
   - Add relationships:
     - `parentInvoice()` - BelongsTo Invoice
     - `childInvoices()` - HasMany Invoice
     - `notifications()` - HasMany RecurringInvoiceNotification
   - Add helper methods:
     - `isRecurring()` - check if invoice is recurring
     - `getNextRecurringDate()` - calculate next occurrence
     - `shouldSendNotification()` - check if notification should be sent
     - `canGenerateNext()` - check if next invoice can be generated

3. **Create RecurringInvoiceNotification Model**
   - Define fillable fields
   - Add relationships to Invoice

### Phase 2: UI Updates

1. **Update Invoice Create Form**
   - Add "Recurring Invoice" checkbox
   - Show recurring settings section when checked:
     - Frequency dropdown (monthly, quarterly, yearly, weekly, biweekly)
     - Start date
     - End date (optional)
     - Notification days before due date (default: 3)
   - Hide/show based on checkbox state

2. **Update Invoice Edit Form**
   - Same recurring settings as create form
   - Show current recurring status
   - Add "Pause/Resume Recurring" button if recurring

3. **Update Invoice Index View**
   - Add "Recurring" badge/indicator for recurring invoices
   - Add filter for recurring invoices
   - Show next recurring date column

4. **Update Invoice Show View**
   - Display recurring information section
   - Show notification history
   - Show parent/child invoice links
   - Add "Generate Next Invoice" button (if applicable)

### Phase 3: Controller Updates

1. **Update InvoiceController**
   - Modify `store()` method to handle recurring fields
   - Modify `update()` method to handle recurring fields
   - Add `toggleRecurring()` method to pause/resume
   - Add `generateNextInvoice()` method to create next occurrence
   - Add `recurringIndex()` method to list all recurring invoices

2. **Validation Rules**
   - Add validation for recurring fields
   - Ensure recurring settings are valid

### Phase 4: Email Notifications

1. **Create Mailable Classes**
   - `ClientInvoiceReminder` - Email to client before due date
   - `AdminInvoiceReminder` - Email to admin emails
   - Both should include:
     - Invoice details
     - Due date
     - Payment link (if applicable)
     - Invoice PDF attachment (optional)

2. **Create Email Templates**
   - `emails/invoices/client-reminder.blade.php`
   - `emails/invoices/admin-reminder.blade.php`
   - Professional, branded email templates

3. **Notification Service**
   - Create `RecurringInvoiceNotificationService`
   - Methods:
     - `sendClientReminder($invoice)` - Send to client
     - `sendAdminReminder($invoice)` - Send to admin emails
     - `scheduleNotifications()` - Schedule notifications for upcoming invoices
   - Handle email failures gracefully

### Phase 5: Scheduled Tasks (Laravel Scheduler)

1. **Create Console Command**
   - `php artisan invoices:check-recurring`
   - Run daily (or multiple times per day)
   - Check for invoices that need:
     - Notifications sent (X days before due date)
     - Next invoice generation (when paid)
   - Log all actions

2. **Update Kernel.php**
   - Schedule the command to run daily at specific time
   - Consider running twice daily for better coverage

### Phase 6: Additional Features

1. **Recurring Invoice Management**
   - List all recurring invoices
   - View recurring invoice history
   - Edit recurring settings
   - Pause/resume functionality
   - Cancel recurring (set end date)

2. **Notification History**
   - View all sent notifications
   - Resend failed notifications
   - View notification details

3. **Auto-Generation Logic**
   - When recurring invoice is marked as "paid"
   - Check if next invoice should be generated
   - Create new invoice with:
     - Same items and amounts
     - Updated dates (invoice_date, due_date)
     - Link to parent invoice
     - Status: "draft" or "sent" (configurable)

## Configuration

### Environment Variables
```env
# Recurring Invoice Settings
RECURRING_INVOICE_NOTIFICATION_DAYS=3
RECURRING_INVOICE_AUTO_GENERATE=true
RECURRING_INVOICE_DEFAULT_STATUS=sent
ADMIN_EMAIL_INVOICES=invoices@raslordeckltd.com
ADMIN_EMAIL_INFO=info@raslordeckltd.com
```

### Config File
Create `config/invoices.php`:
```php
'recurring' => [
    'notification_days_before' => env('RECURRING_INVOICE_NOTIFICATION_DAYS', 3),
    'auto_generate' => env('RECURRING_INVOICE_AUTO_GENERATE', true),
    'default_status' => env('RECURRING_INVOICE_DEFAULT_STATUS', 'sent'),
    'admin_emails' => [
        env('ADMIN_EMAIL_INVOICES', 'invoices@raslordeckltd.com'),
        env('ADMIN_EMAIL_INFO', 'info@raslordeckltd.com'),
    ],
]
```

## File Structure

```
app/
├── Models/
│   ├── Invoice.php (updated)
│   └── RecurringInvoiceNotification.php (new)
├── Http/Controllers/
│   └── InvoiceController.php (updated)
├── Mail/
│   ├── ClientInvoiceReminder.php (new)
│   └── AdminInvoiceReminder.php (new)
├── Services/
│   └── RecurringInvoiceNotificationService.php (new)
└── Console/Commands/
    └── CheckRecurringInvoices.php (new)

resources/views/
├── invoices/
│   ├── create.blade.php (updated)
│   ├── edit.blade.php (updated)
│   ├── index.blade.php (updated)
│   ├── show.blade.php (updated)
│   └── recurring.blade.php (new)
└── emails/
    └── invoices/
        ├── client-reminder.blade.php (new)
        └── admin-reminder.blade.php (new)

database/migrations/
├── YYYY_MM_DD_HHMMSS_add_recurring_fields_to_invoices_table.php (new)
└── YYYY_MM_DD_HHMMSS_create_recurring_invoice_notifications_table.php (new)
```

## Testing Checklist

- [ ] Create recurring invoice
- [ ] Edit recurring invoice settings
- [ ] Pause/resume recurring invoice
- [ ] Client notification sent X days before due date
- [ ] Admin notifications sent to both emails
- [ ] Next invoice auto-generated when paid
- [ ] Notification history tracked
- [ ] Failed notifications handled
- [ ] Recurring invoice list view
- [ ] Filter recurring invoices
- [ ] Cancel recurring invoice

## Security Considerations

1. Only admins/super-admins can create/edit recurring invoices
2. Email addresses validated before sending
3. Rate limiting for email sending
4. Log all notification attempts
5. Handle email service failures gracefully

## Performance Considerations

1. Index database columns used in queries (next_recurring_date, is_recurring)
2. Batch process notifications to avoid overwhelming email service
3. Cache recurring invoice settings
4. Optimize scheduled command queries

## Future Enhancements

1. Custom recurrence patterns (e.g., every 2 months, specific days)
2. Email templates customization per client
3. SMS notifications
4. Payment gateway integration for auto-payment
5. Recurring invoice analytics and reports
6. Webhook support for external integrations
