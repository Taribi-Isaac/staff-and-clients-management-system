#!/bin/bash
# Script to properly switch to raslorde_admin database

echo "🔄 Switching to raslorde_admin database..."

cd ~/site_files

# 1. Check current .env setting
echo "📋 Current database setting:"
grep "DB_DATABASE" .env

# 2. Update .env file
echo ""
echo "📝 Updating .env file..."
sed -i 's/DB_DATABASE=raslorde_controlDB/DB_DATABASE=raslorde_admin/' .env

# 3. Verify the change
echo ""
echo "✅ Updated database setting:"
grep "DB_DATABASE" .env

# 4. Clear ALL caches
echo ""
echo "🧹 Clearing all caches..."
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear

# 5. Verify database connection
echo ""
echo "🔍 Verifying database connection..."
php artisan tinker --execute="
echo 'Current database: ' . DB::connection()->getDatabaseName() . PHP_EOL;
"

echo ""
echo "✅ Database switch completed!"
echo ""
echo "Next: Run php create_tables_in_admin_db.php to create invoice tables"

