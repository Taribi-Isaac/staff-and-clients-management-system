#!/bin/bash
# Script to create invoice_items table and mark migration as run

echo "🔧 Fixing invoice_items table issue..."

cd ~/site_files

# Check if invoice_items table exists
echo "📋 Checking if invoice_items table exists..."
php artisan tinker --execute="
if (Schema::hasTable('invoice_items')) {
    echo 'Table invoice_items already exists\n';
    exit(0);
} else {
    echo 'Table invoice_items does not exist. Creating...\n';
    exit(1);
}
"

if [ $? -ne 0 ]; then
    echo "📦 Creating invoice_items table..."
    
    # Run the migration
    php artisan migrate --path=database/migrations/2025_11_28_105722_create_invoice_items_table.php --force
    
    # If migration fails, create table manually
    if [ $? -ne 0 ]; then
        echo "⚠️  Migration failed, creating table manually..."
        php artisan tinker --execute="
        Schema::create('invoice_items', function (\$table) {
            \$table->id();
            \$table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            \$table->string('description');
            \$table->integer('quantity')->default(1);
            \$table->decimal('unit_price', 15, 2);
            \$table->decimal('total', 15, 2);
            \$table->integer('order')->default(0);
            \$table->timestamps();
        });
        echo 'Table created successfully\n';
        "
    fi
fi

# Mark migration as run
echo "📝 Marking migration as run..."
php artisan tinker --execute="
if (!DB::table('migrations')->where('migration', '2025_11_28_105722_create_invoice_items_table')->exists()) {
    \$batch = DB::table('migrations')->max('batch') ?? 0;
    DB::table('migrations')->insert([
        'migration' => '2025_11_28_105722_create_invoice_items_table',
        'batch' => \$batch + 1
    ]);
    echo 'Migration marked as run\n';
} else {
    echo 'Migration already marked as run\n';
}
"

echo ""
echo "✅ Invoice items table fix completed!"
echo ""
echo "Verify by running: php artisan tinker"
echo "Then: Schema::hasTable('invoice_items')"
echo ""






















