<?php

namespace App\Console\Commands;

use App\Services\RecurringInvoiceNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckRecurringInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:check-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and send notifications for recurring invoices, and generate next invoices';

    protected $notificationService;

    /**
     * Create a new command instance.
     */
    public function __construct(RecurringInvoiceNotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking recurring invoices...');

        // Process notifications
        $this->info('Processing notifications...');
        $notificationResults = $this->notificationService->processNotifications();
        
        $this->info("Client reminders sent: {$notificationResults['client_reminders_sent']}");
        $this->info("Admin reminders sent: {$notificationResults['admin_reminders_sent']}");
        
        if ($notificationResults['errors'] > 0) {
            $this->warn("Errors encountered: {$notificationResults['errors']}");
        }

        // Process invoice generation
        $this->info('Processing invoice generation...');
        $generationResults = $this->notificationService->processInvoiceGeneration();
        
        $this->info("Invoices generated: {$generationResults['generated']}");
        
        if ($generationResults['errors'] > 0) {
            $this->warn("Errors encountered: {$generationResults['errors']}");
        }

        // Log summary
        Log::info('Recurring invoices check completed', [
            'notifications' => $notificationResults,
            'generation' => $generationResults,
        ]);

        $this->info('Recurring invoices check completed successfully!');
        
        return Command::SUCCESS;
    }
}
