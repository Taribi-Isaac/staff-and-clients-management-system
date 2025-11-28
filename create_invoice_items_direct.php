<?php
/**
 * Script to create invoice_items table directly
 * Run: php create_invoice_items_direct.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "📋 Creating invoice_items table...\n\n";

// Check if table already exists
if (Schema::hasTable('invoice_items')) {
    echo "✅ Table 'invoice_items' already exists!\n";
    exit(0);
}

// Check if invoices table exists
if (!Schema::hasTable('invoices')) {
    echo "❌ Error: 'invoices' table does not exist!\n";
    echo "Please create the invoices table first.\n";
    exit(1);
}

try {
    // Create the table
    Schema::create('invoice_items', function ($table) {
        $table->id();
        $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
        $table->string('description');
        $table->integer('quantity')->default(1);
        $table->decimal('unit_price', 15, 2);
        $table->decimal('total', 15, 2);
        $table->integer('order')->default(0);
        $table->timestamps();
    });
    
    echo "✅ Table 'invoice_items' created successfully!\n\n";
    
    // Verify it was created
    if (Schema::hasTable('invoice_items')) {
        echo "✅ Verification: Table exists!\n";
    } else {
        echo "⚠️  Warning: Table creation may have failed.\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error creating table: " . $e->getMessage() . "\n";
    echo "\nTrying alternative method (without foreign key first)...\n";
    
    // Try creating without foreign key first
    try {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `invoice_items` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `invoice_id` bigint(20) unsigned NOT NULL,
              `description` varchar(255) NOT NULL,
              `quantity` int(11) NOT NULL DEFAULT '1',
              `unit_price` decimal(15,2) NOT NULL,
              `total` decimal(15,2) NOT NULL,
              `order` int(11) NOT NULL DEFAULT '0',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `invoice_items_invoice_id_foreign` (`invoice_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Add foreign key constraint separately
        DB::statement("
            ALTER TABLE `invoice_items`
            ADD CONSTRAINT `invoice_items_invoice_id_foreign`
            FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
        ");
        
        echo "✅ Table created using alternative method!\n";
        
    } catch (\Exception $e2) {
        echo "❌ Alternative method also failed: " . $e2->getMessage() . "\n";
        exit(1);
    }
}

echo "\n✅ Done! The invoice_items table should now exist.\n";

