<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\RecurringInvoiceNotification;
use App\Mail\ClientInvoiceReminder;
use App\Mail\AdminInvoiceReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RecurringInvoiceNotificationService
{
    /**
     * Send client reminder for an invoice
     */
    public function sendClientReminder(Invoice $invoice): bool
    {
        if (!$invoice->client_email) {
            Log::warning("Cannot send client reminder: Invoice {$invoice->id} has no client email");
            return false;
        }

        try {
            $daysUntilDue = $invoice->due_date ? now()->diffInDays($invoice->due_date, false) : 0;
            
            Mail::to($invoice->client_email)->send(new ClientInvoiceReminder($invoice, $daysUntilDue));
            
            // Log notification
            $this->logNotification($invoice, 'client_reminder', $invoice->client_email, true);
            
            // Update invoice last notification sent timestamp
            $invoice->update(['last_notification_sent_at' => now()]);
            
            Log::info("Client reminder sent for invoice {$invoice->invoice_number} to {$invoice->client_email}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send client reminder for invoice {$invoice->id}: " . $e->getMessage());
            
            // Log failed notification
            $this->logNotification($invoice, 'client_reminder', $invoice->client_email, false, $e->getMessage());
            
            return false;
        }
    }

    /**
     * Send admin reminder for an invoice
     */
    public function sendAdminReminder(Invoice $invoice): bool
    {
        $adminEmails = config('invoices.recurring.admin_emails', []);
        
        if (empty($adminEmails)) {
            Log::warning("No admin emails configured for invoice reminders");
            return false;
        }

        $daysUntilDue = $invoice->due_date ? now()->diffInDays($invoice->due_date, false) : 0;
        $success = true;

        foreach ($adminEmails as $email) {
            try {
                Mail::to($email)->send(new AdminInvoiceReminder($invoice, $daysUntilDue));
                
                // Log notification
                $this->logNotification($invoice, 'admin_reminder', $email, true);
                
                Log::info("Admin reminder sent for invoice {$invoice->invoice_number} to {$email}");
            } catch (\Exception $e) {
                Log::error("Failed to send admin reminder for invoice {$invoice->id} to {$email}: " . $e->getMessage());
                
                // Log failed notification
                $this->logNotification($invoice, 'admin_reminder', $email, false, $e->getMessage());
                
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Check and send notifications for invoices that need reminders
     */
    public function processNotifications(): array
    {
        $results = [
            'client_reminders_sent' => 0,
            'admin_reminders_sent' => 0,
            'errors' => 0,
        ];

        // Get all active recurring invoices that should receive notifications
        $invoices = Invoice::where('is_recurring', true)
            ->where('is_recurring_paused', false)
            ->whereNotNull('due_date')
            ->where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($invoices as $invoice) {
            if ($invoice->shouldSendNotification()) {
                // Send client reminder
                if ($this->sendClientReminder($invoice)) {
                    $results['client_reminders_sent']++;
                } else {
                    $results['errors']++;
                }

                // Send admin reminder
                if ($this->sendAdminReminder($invoice)) {
                    $results['admin_reminders_sent']++;
                } else {
                    $results['errors']++;
                }
            }
        }

        return $results;
    }

    /**
     * Log notification to database
     */
    protected function logNotification(
        Invoice $invoice,
        string $type,
        string $sentTo,
        bool $success,
        ?string $errorMessage = null
    ): void {
        RecurringInvoiceNotification::create([
            'invoice_id' => $invoice->id,
            'notification_type' => $type,
            'sent_to' => $sentTo,
            'sent_at' => $success ? now() : null,
            'scheduled_for_date' => now()->toDateString(),
            'status' => $success ? 'sent' : 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Generate next invoice for a recurring invoice
     */
    public function generateNextInvoice(Invoice $parentInvoice): ?Invoice
    {
        if (!$parentInvoice->canGenerateNext()) {
            return null;
        }

        try {
            $nextDate = $parentInvoice->getNextRecurringDate();
            if (!$nextDate) {
                return null;
            }

            // Calculate new invoice and due dates based on frequency
            $invoiceDate = $nextDate->copy();
            $dueDate = $nextDate->copy();

            // Create new invoice
            $newInvoice = $parentInvoice->replicate();
            $newInvoice->invoice_number = null; // Will be auto-generated
            $newInvoice->invoice_date = $invoiceDate;
            $newInvoice->due_date = $dueDate;
            $newInvoice->status = config('invoices.recurring.default_status', 'sent');
            $newInvoice->parent_invoice_id = $parentInvoice->id;
            $newInvoice->created_by = $parentInvoice->created_by;
            $newInvoice->last_notification_sent_at = null;
            $newInvoice->save();

            // Copy invoice items
            foreach ($parentInvoice->items as $item) {
                $newItem = $item->replicate();
                $newItem->invoice_id = $newInvoice->id;
                $newItem->save();
            }

            // Update parent invoice's next_recurring_date
            $parentInvoice->update([
                'next_recurring_date' => $parentInvoice->getNextRecurringDate(),
            ]);

            Log::info("Generated next invoice {$newInvoice->invoice_number} for recurring invoice {$parentInvoice->invoice_number}");

            return $newInvoice;
        } catch (\Exception $e) {
            Log::error("Failed to generate next invoice for {$parentInvoice->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Process invoice generation for paid recurring invoices
     */
    public function processInvoiceGeneration(): array
    {
        $results = [
            'generated' => 0,
            'errors' => 0,
        ];

        if (!config('invoices.recurring.auto_generate', true)) {
            return $results;
        }

        // Get parent invoices that are paid and can generate next invoice
        $parentInvoices = Invoice::where('is_recurring', true)
            ->where('is_recurring_paused', false)
            ->where('status', 'paid')
            ->whereNull('parent_invoice_id') // Only parent invoices can generate children
            ->get();

        foreach ($parentInvoices as $invoice) {
            if ($invoice->canGenerateNext()) {
                $newInvoice = $this->generateNextInvoice($invoice);
                if ($newInvoice) {
                    $results['generated']++;
                } else {
                    $results['errors']++;
                }
            }
        }

        return $results;
    }
}
