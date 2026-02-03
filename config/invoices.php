<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Recurring Invoice Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for recurring invoices and notifications.
    |
    */

    'recurring' => [
        // Number of days before due date to send notification
        'notification_days_before' => env('RECURRING_INVOICE_NOTIFICATION_DAYS', 3),

        // Automatically generate next invoice when current one is paid
        'auto_generate' => env('RECURRING_INVOICE_AUTO_GENERATE', true),

        // Default status for newly generated recurring invoices
        'default_status' => env('RECURRING_INVOICE_DEFAULT_STATUS', 'sent'),

        // Admin email addresses to receive reminders
        'admin_emails' => [
            env('ADMIN_EMAIL_INVOICES', 'invoices@raslordeckltd.com'),
            env('ADMIN_EMAIL_INFO', 'info@raslordeckltd.com'),
        ],
    ],
];
