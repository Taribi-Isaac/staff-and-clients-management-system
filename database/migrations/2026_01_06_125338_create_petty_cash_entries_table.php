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
        Schema::create('petty_cash_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->enum('transaction_type', ['expense', 'replenishment'])->default('expense');
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->string('category')->nullable()->comment('e.g., Office Supplies, Transport, Meals, etc.');
            $table->string('receipt_number')->nullable();
            $table->string('approved_by')->nullable()->comment('Name of person who approved');
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('entry_date');
            $table->index('transaction_type');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_entries');
    }
};
