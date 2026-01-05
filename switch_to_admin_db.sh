#!/bin/bash
# Script to switch to raslorde_admin database and create invoice tables

echo "🔄 Switching to raslorde_admin database..."

cd ~/site_files

# Backup current .env
echo "💾 Backing up current .env..."
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)

# Update .env to use raslorde_admin database
echo "📝 Updating .env file..."
sed -i 's/DB_DATABASE=raslorde_controlDB/DB_DATABASE=raslorde_admin/' .env

# Verify the change
echo ""
echo "✅ Database updated. Current database setting:"
grep "DB_DATABASE" .env

# Clear config cache
echo ""
echo "🧹 Clearing config cache..."
php artisan config:clear

echo ""
echo "📋 Next steps:"
echo "1. Create invoices table in raslorde_admin (if it doesn't exist)"
echo "2. Create invoice_items table in raslorde_admin"
echo "3. Mark migrations as run"
echo ""
echo "Run: php create_tables_in_admin_db.php"






















