<?php
/**
 * Script to mark invoice migrations as run
 * Run: php mark_migrations_run.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "📋 Marking invoice migrations as run...\n\n";

$migrations = [
    '2025_11_28_105717_create_invoices_table',
    '2025_11_28_105722_create_invoice_items_table',
];

$batch = DB::table('migrations')->max('batch') ?? 0;
$batch++;

foreach ($migrations as $migration) {
    $exists = DB::table('migrations')->where('migration', $migration)->exists();
    
    if ($exists) {
        echo "✅ Migration '{$migration}' already marked as run\n";
    } else {
        // Check if table exists
        $tableName = null;
        if (strpos($migration, 'invoices_table') !== false) {
            $tableName = 'invoices';
        } elseif (strpos($migration, 'invoice_items_table') !== false) {
            $tableName = 'invoice_items';
        }
        
        if ($tableName && Schema::hasTable($tableName)) {
            DB::table('migrations')->insert([
                'migration' => $migration,
                'batch' => $batch
            ]);
            echo "✅ Migration '{$migration}' marked as run (table exists)\n";
        } else {
            echo "⚠️  Migration '{$migration}' - table doesn't exist, skipping\n";
        }
    }
}

echo "\n✅ Done!\n";
echo "You can now run: php artisan migrate\n";


























