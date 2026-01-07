<?php
/**
 * Script to add 'quote' to invoices type enum on production server
 * Run: php run_quote_migration_on_server.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "📋 Adding 'quote' to invoices type enum...\n\n";

// Check current database
$currentDb = DB::connection()->getDatabaseName();
echo "Current database: {$currentDb}\n\n";

// Check if invoices table exists
if (!Schema::hasTable('invoices')) {
    echo "❌ Error: 'invoices' table does not exist!\n";
    exit(1);
}

try {
    // Get current column definition
    $columnInfo = DB::select("SHOW COLUMNS FROM invoices WHERE Field = 'type'");
    if (empty($columnInfo)) {
        echo "❌ Error: 'type' column not found!\n";
        exit(1);
    }
    
    echo "Current type definition:\n";
    print_r($columnInfo[0]);
    echo "\n";
    
    // Modify the enum to include 'quote'
    echo "📝 Modifying enum to include 'quote'...\n";
    DB::statement("ALTER TABLE `invoices` MODIFY COLUMN `type` ENUM('invoice', 'receipt', 'quote') NOT NULL DEFAULT 'invoice'");
    
    echo "✅ Successfully added 'quote' to type enum!\n";
    
    // Verify
    $columnInfo = DB::select("SHOW COLUMNS FROM invoices WHERE Field = 'type'");
    echo "\nUpdated type definition:\n";
    print_r($columnInfo[0]);
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Mark migration as run
echo "\n📝 Marking migration as run...\n";
try {
    if (!DB::table('migrations')->where('migration', '2025_11_28_150259_add_quote_type_to_invoices_table')->exists()) {
        $batch = DB::table('migrations')->max('batch') ?? 0;
        DB::table('migrations')->insert([
            'migration' => '2025_11_28_150259_add_quote_type_to_invoices_table',
            'batch' => $batch + 1
        ]);
        echo "✅ Migration marked as run!\n";
    } else {
        echo "✅ Migration already marked as run!\n";
    }
} catch (\Exception $e) {
    echo "⚠️  Could not mark migration: " . $e->getMessage() . "\n";
}

echo "\n✅ All done! You can now create quotes.\n";























