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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('business_name')->nullable();
            $table->string('location')->nullable();
            $table->string('account_number')->unique();
            $table->string('dish_serial_number')->nullable();
            $table->string('kit_number')->nullable();
            $table->string('starlink_id')->nullable();
            $table->string('subscription_duration')->nullable();
            $table->string('subscription_start_date')->nullable();
            $table->string('subscription_end_date')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->text('service_address')->nullable();
            $table->string('account_name')->nullable();
            $table->text('card_details')->nullable();
            $table->string('status')->default('inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
