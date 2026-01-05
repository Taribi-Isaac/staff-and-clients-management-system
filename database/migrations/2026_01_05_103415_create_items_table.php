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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('item_code')->unique()->nullable()->comment('SKU or Item Code');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->text('description')->nullable();
            $table->string('unit')->default('piece')->comment('piece, box, kg, liter, etc.');
            $table->integer('quantity')->default(0)->comment('Current stock quantity');
            $table->integer('min_stock_level')->default(0)->comment('Minimum stock level for alerts');
            $table->decimal('unit_price', 15, 2)->default(0.00);
            $table->date('purchase_date')->nullable();
            $table->string('location')->nullable()->comment('Warehouse or storage location');
            $table->enum('status', ['available', 'low_stock', 'out_of_stock', 'discontinued'])->default('available');
            $table->boolean('is_consumable')->default(false)->comment('true for consumables, false for non-consumables');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
