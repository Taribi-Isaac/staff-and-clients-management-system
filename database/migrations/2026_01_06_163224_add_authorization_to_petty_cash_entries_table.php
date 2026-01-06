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
            $table->enum('authorization_status', ['pending', 'authorized', 'unauthorized'])->default('pending')->after('approved_by_user_id');
            $table->foreignId('authorized_by_user_id')->nullable()->after('authorization_status')->constrained('users')->onDelete('set null');
            $table->timestamp('authorized_at')->nullable()->after('authorized_by_user_id');
            $table->text('authorization_notes')->nullable()->after('authorized_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petty_cash_entries', function (Blueprint $table) {
            $table->dropForeign(['authorized_by_user_id']);
            $table->dropColumn(['authorization_status', 'authorized_by_user_id', 'authorized_at', 'authorization_notes']);
        });
    }
};
