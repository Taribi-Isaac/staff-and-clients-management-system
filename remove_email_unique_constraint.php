<?php
/**
 * Script to remove unique constraint from clients.email column in production
 * Run: php remove_email_unique_constraint.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "📋 Removing unique constraint from clients.email column...\n\n";

// Check current database
$currentDb = DB::connection()->getDatabaseName();
echo "Current database: {$currentDb}\n\n";

// Check if clients table exists
if (!Schema::hasTable('clients')) {
    echo "❌ Error: 'clients' table does not exist!\n";
    exit(1);
}

try {
    // Check if unique constraint exists
    $indexes = DB::select("SHOW INDEXES FROM clients WHERE Column_name = 'email' AND Non_unique = 0");
    
    if (empty($indexes)) {
        echo "✅ No unique constraint found on email column. It's already non-unique.\n";
    } else {
        echo "Found unique constraint(s) on email column:\n";
        foreach ($indexes as $index) {
            echo "  - Key name: {$index->Key_name}\n";
        }
        echo "\n";
        
        // Remove unique constraint
        echo "📝 Removing unique constraint...\n";
        foreach ($indexes as $index) {
            DB::statement("ALTER TABLE `clients` DROP INDEX `{$index->Key_name}`");
            echo "✅ Removed index: {$index->Key_name}\n";
        }
        
        echo "\n✅ Successfully removed unique constraint from email column!\n";
    }
    
    // Verify
    $indexes = DB::select("SHOW INDEXES FROM clients WHERE Column_name = 'email' AND Non_unique = 0");
    if (empty($indexes)) {
        echo "✅ Verification: Email column is now non-unique.\n";
    } else {
        echo "⚠️  Warning: Unique constraint still exists!\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✅ All done! Multiple clients can now have the same email.\n";


























