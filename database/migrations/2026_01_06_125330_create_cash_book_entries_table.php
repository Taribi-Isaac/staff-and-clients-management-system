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
        Schema::create('cash_book_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->enum('transaction_type', ['receipt', 'payment'])->default('receipt');
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->string('reference')->nullable()->comment('Reference number, receipt number, etc.');
            $table->decimal('balance', 15, 2)->default(0)->comment('Running balance after this entry');
            $table->foreignId('related_invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->foreignId('related_client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('entry_date');
            $table->index('transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_book_entries');
    }
};
