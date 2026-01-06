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
        Schema::table('petty_cash_entries', function (Blueprint $table) {
            $table->string('receiver_beneficiary')->nullable()->after('description')->comment('Name of person who received the payment or beneficiary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petty_cash_entries', function (Blueprint $table) {
            $table->dropColumn('receiver_beneficiary');
        });
    }
};
