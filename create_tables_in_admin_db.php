<?php
/**
 * Script to create invoice tables in raslorde_admin database
 * Run: php create_tables_in_admin_db.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "📋 Creating invoice tables in raslorde_admin database...\n\n";

// Check current database
$currentDb = DB::connection()->getDatabaseName();
echo "Current database: {$currentDb}\n\n";

// 1. Create invoices table if it doesn't exist
echo "1️⃣  Checking invoices table...\n";
if (Schema::hasTable('invoices')) {
    echo "   ✅ Table 'invoices' already exists!\n";
} else {
    echo "   📦 Creating 'invoices' table...\n";
    try {
        Schema::create('invoices', function ($table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->enum('type', ['invoice', 'receipt'])->default('invoice');
            $table->string('title')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_address')->nullable();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
        echo "   ✅ Table 'invoices' created successfully!\n";
    } catch (\Exception $e) {
        echo "   ⚠️  Error: " . $e->getMessage() . "\n";
        echo "   Trying alternative method...\n";
        
        // Try without foreign keys first
        DB::statement("
            CREATE TABLE IF NOT EXISTS `invoices` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `invoice_number` varchar(255) NOT NULL,
              `type` enum('invoice','receipt') NOT NULL DEFAULT 'invoice',
              `title` varchar(255) DEFAULT NULL,
              `client_id` bigint(20) unsigned DEFAULT NULL,
              `client_name` varchar(255) DEFAULT NULL,
              `client_email` varchar(255) DEFAULT NULL,
              `client_address` varchar(255) DEFAULT NULL,
              `invoice_date` date NOT NULL,
              `due_date` date DEFAULT NULL,
              `notes` text,
              `terms` text,
              `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
              `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
              `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
              `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
              `total` decimal(15,2) NOT NULL DEFAULT '0.00',
              `status` enum('draft','sent','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
              `created_by` bigint(20) unsigned NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
              KEY `invoices_client_id_foreign` (`client_id`),
              KEY `invoices_created_by_foreign` (`created_by`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Add foreign keys if tables exist
        if (Schema::hasTable('clients')) {
            try {
                DB::statement("
                    ALTER TABLE `invoices`
                    ADD CONSTRAINT `invoices_client_id_foreign`
                    FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL
                ");
            } catch (\Exception $e) {
                echo "   ⚠️  Could not add client_id foreign key (may already exist)\n";
            }
        }
        
        if (Schema::hasTable('users')) {
            try {
                DB::statement("
                    ALTER TABLE `invoices`
                    ADD CONSTRAINT `invoices_created_by_foreign`
                    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
                ");
            } catch (\Exception $e) {
                echo "   ⚠️  Could not add created_by foreign key (may already exist)\n";
            }
        }
        
        echo "   ✅ Table 'invoices' created using alternative method!\n";
    }
}

echo "\n";

// 2. Create invoice_items table if it doesn't exist
echo "2️⃣  Checking invoice_items table...\n";
if (Schema::hasTable('invoice_items')) {
    echo "   ✅ Table 'invoice_items' already exists!\n";
} else {
    echo "   📦 Creating 'invoice_items' table...\n";
    try {
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
        echo "   ✅ Table 'invoice_items' created successfully!\n";
    } catch (\Exception $e) {
        echo "   ⚠️  Error: " . $e->getMessage() . "\n";
        echo "   Trying alternative method...\n";
        
        // Create without foreign key first
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
        
        // Add foreign key
        if (Schema::hasTable('invoices')) {
            try {
                DB::statement("
                    ALTER TABLE `invoice_items`
                    ADD CONSTRAINT `invoice_items_invoice_id_foreign`
                    FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
                ");
            } catch (\Exception $e) {
                echo "   ⚠️  Could not add foreign key (may already exist)\n";
            }
        }
        
        echo "   ✅ Table 'invoice_items' created using alternative method!\n";
    }
}

echo "\n";

// 3. Mark migrations as run
echo "3️⃣  Marking migrations as run...\n";
$migrations = [
    '2025_11_28_105717_create_invoices_table',
    '2025_11_28_105722_create_invoice_items_table',
];

$batch = DB::table('migrations')->max('batch') ?? 0;
$batch++;

foreach ($migrations as $migration) {
    $exists = DB::table('migrations')->where('migration', $migration)->exists();
    
    if ($exists) {
        echo "   ✅ Migration '{$migration}' already marked as run\n";
    } else {
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => $batch
        ]);
        echo "   ✅ Migration '{$migration}' marked as run\n";
    }
}

echo "\n";
echo "✅ All done! Invoice tables are ready in raslorde_admin database.\n";
echo "\n";
echo "Next steps:"
echo "1. Clear config cache: php artisan config:clear"
echo "2. Test the application"
echo "\n";

