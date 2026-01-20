#!/bin/bash
# Quick script to fix quote enum issue on server

echo "🔧 Fixing quote enum in production database..."

cd ~/site_files

# Run the migration
echo "📋 Running quote migration..."
php artisan migrate --path=database/migrations/2025_11_28_150259_add_quote_type_to_invoices_table.php --force

if [ $? -eq 0 ]; then
    echo "✅ Migration completed successfully!"
else
    echo "⚠️  Migration failed, trying alternative method..."
    echo ""
    echo "Run this SQL manually in phpMyAdmin:"
    echo "ALTER TABLE \`invoices\` MODIFY COLUMN \`type\` ENUM('invoice', 'receipt', 'quote') NOT NULL DEFAULT 'invoice';"
fi


























