#!/bin/bash
# Script to fix production issues

echo "🔧 Fixing production issues..."

cd ~/site_files

# 1. Clear all caches to fix undefined variables
echo "🧹 Clearing all caches..."
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Remove compiled views
echo "🗑️  Removing compiled views..."
rm -rf storage/framework/views/*

# 2. Mark invoices migration as run (since table already exists)
echo "📋 Marking invoices migration as run..."
php artisan migrate:status | grep invoices || echo "Checking migration status..."

# Insert into migrations table if not exists
php artisan tinker --execute="
if (!DB::table('migrations')->where('migration', '2025_11_28_105717_create_invoices_table')->exists()) {
    DB::table('migrations')->insert(['migration' => '2025_11_28_105717_create_invoices_table', 'batch' => DB::table('migrations')->max('batch') + 1]);
    echo 'Migration marked as run\n';
} else {
    echo 'Migration already marked as run\n';
}
"

# Also mark invoice_items migration
php artisan tinker --execute="
if (!DB::table('migrations')->where('migration', '2025_11_28_105722_create_invoice_items_table')->exists()) {
    DB::table('migrations')->insert(['migration' => '2025_11_28_105722_create_invoice_items_table', 'batch' => DB::table('migrations')->max('batch') + 1]);
    echo 'Invoice items migration marked as run\n';
} else {
    echo 'Invoice items migration already marked as run\n';
}
"

# 3. Rebuild caches
echo "⚡ Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chmod -R 755 storage/framework

echo ""
echo "✅ Production fixes completed!"
echo ""
echo "Next steps:"
echo "1. Refresh your browser"
echo "2. The home page should now show all counts correctly"
echo "3. You can now run new migrations if needed"
echo ""


























