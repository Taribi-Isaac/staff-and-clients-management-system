<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recurring_invoice_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->enum('notification_type', ['client_reminder', 'admin_reminder']);
            $table->string('sent_to'); // email address
            $table->timestamp('sent_at')->nullable();
            $table->date('scheduled_for_date'); // when notification was scheduled for
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            // Add indexes for better query performance
            $table->index(['invoice_id', 'status']);
            $table->index('scheduled_for_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_invoice_notifications');
    }
};
