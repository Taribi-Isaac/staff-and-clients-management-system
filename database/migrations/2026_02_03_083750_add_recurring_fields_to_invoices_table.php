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
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('status');
            $table->enum('recurring_frequency', ['weekly', 'biweekly', 'monthly', 'quarterly', 'yearly'])->nullable()->after('is_recurring');
            $table->date('recurring_start_date')->nullable()->after('recurring_frequency');
            $table->date('recurring_end_date')->nullable()->after('recurring_start_date');
            $table->date('next_recurring_date')->nullable()->after('recurring_end_date');
            $table->foreignId('parent_invoice_id')->nullable()->after('next_recurring_date')->constrained('invoices')->onDelete('cascade');
            $table->integer('notification_days_before')->default(3)->after('parent_invoice_id');
            $table->timestamp('last_notification_sent_at')->nullable()->after('notification_days_before');
            $table->boolean('is_recurring_paused')->default(false)->after('last_notification_sent_at');
            
            // Add index for better query performance
            $table->index(['is_recurring', 'next_recurring_date']);
            $table->index('parent_invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['parent_invoice_id']);
            $table->dropIndex(['is_recurring', 'next_recurring_date']);
            $table->dropIndex(['parent_invoice_id']);
            $table->dropColumn([
                'is_recurring',
                'recurring_frequency',
                'recurring_start_date',
                'recurring_end_date',
                'next_recurring_date',
                'parent_invoice_id',
                'notification_days_before',
                'last_notification_sent_at',
                'is_recurring_paused',
            ]);
        });
    }
};
