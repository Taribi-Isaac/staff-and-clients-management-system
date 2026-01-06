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
        Schema::create('payroll_book_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->date('pay_period_start');
            $table->date('pay_period_end');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('employee_name');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowances', 15, 2)->default(0)->comment('Total allowances');
            $table->decimal('deductions', 15, 2)->default(0)->comment('Total deductions (tax, pension, etc.)');
            $table->decimal('net_pay', 15, 2)->comment('Net pay after deductions');
            $table->enum('payment_method', ['bank_transfer', 'cash', 'cheque'])->default('bank_transfer');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('entry_date');
            $table->index('employee_id');
            $table->index('payment_status');
            $table->index('pay_period_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_book_entries');
    }
};
