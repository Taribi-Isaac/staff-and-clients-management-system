<?php
/**
 * Quick script to create invoice_items table in raslorde_admin
 * Run: php create_invoice_items_in_admin.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "📋 Creating invoice_items table in raslorde_admin...\n\n";

// Check current database
$currentDb = DB::connection()->getDatabaseName();
echo "Current database: {$currentDb}\n";

if ($currentDb !== 'raslorde_admin') {
    echo "⚠️  WARNING: You're not connected to raslorde_admin!\n";
    echo "Please update .env file and clear config cache first.\n";
    echo "Run: php fix_database_switch.sh\n";
    exit(1);
}

// Check if invoice_items table exists
if (Schema::hasTable('invoice_items')) {
    echo "✅ Table 'invoice_items' already exists!\n";
    exit(0);
}

// Check if invoices table exists
if (!Schema::hasTable('invoices')) {
    echo "❌ Error: 'invoices' table does not exist in raslorde_admin!\n";
    echo "Please create it first or run: php create_tables_in_admin_db.php\n";
    exit(1);
}

echo "📦 Creating 'invoice_items' table...\n";

try {
    // Create table without foreign key first
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
    
    echo "✅ Table structure created!\n";
    
    // Add foreign key constraint
    echo "🔗 Adding foreign key constraint...\n";
    try {
        DB::statement("
            ALTER TABLE `invoice_items`
            ADD CONSTRAINT `invoice_items_invoice_id_foreign`
            FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
        ");
        echo "✅ Foreign key added successfully!\n";
    } catch (\Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false || 
            strpos($e->getMessage(), 'already exists') !== false) {
            echo "⚠️  Foreign key already exists (this is okay)\n";
        } else {
            echo "⚠️  Could not add foreign key: " . $e->getMessage() . "\n";
            echo "   Table created without foreign key constraint.\n";
        }
    }
    
    // Verify
    if (Schema::hasTable('invoice_items')) {
        echo "\n✅ SUCCESS! Table 'invoice_items' created in raslorde_admin!\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Mark migration as run
echo "\n📝 Marking migration as run...\n";
try {
    if (!DB::table('migrations')->where('migration', '2025_11_28_105722_create_invoice_items_table')->exists()) {
        $batch = DB::table('migrations')->max('batch') ?? 0;
        DB::table('migrations')->insert([
            'migration' => '2025_11_28_105722_create_invoice_items_table',
            'batch' => $batch + 1
        ]);
        echo "✅ Migration marked as run!\n";
    } else {
        echo "✅ Migration already marked as run!\n";
    }
} catch (\Exception $e) {
    echo "⚠️  Could not mark migration: " . $e->getMessage() . "\n";
}

echo "\n✅ All done!\n";

