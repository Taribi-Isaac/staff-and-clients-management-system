#!/bin/bash
# Script to verify database connection and create invoice tables

echo "🔍 Verifying database connection to raslorde_admin..."

cd ~/site_files

# 1. Clear all caches
echo "🧹 Clearing caches..."
php artisan optimize:clear
php artisan config:clear

# 2. Verify database connection
echo ""
echo "📋 Verifying database connection..."
php artisan tinker --execute="
try {
    \$db = DB::connection()->getDatabaseName();
    echo '✅ Connected to database: ' . \$db . PHP_EOL;
    
    if (\$db === 'raslorde_admin') {
        echo '✅ Correct database!' . PHP_EOL;
    } else {
        echo '⚠️  Wrong database! Expected: raslorde_admin' . PHP_EOL;
    }
    
    // Test query
    \$count = DB::table('users')->count();
    echo '✅ Database connection works! Users count: ' . \$count . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Database connection failed: ' . \$e->getMessage() . PHP_EOL;
    exit(1);
}
"

if [ $? -eq 0 ]; then
    echo ""
    echo "📦 Creating invoice tables..."
    php create_tables_in_admin_db.php
else
    echo ""
    echo "❌ Database connection failed. Please check your .env file."
    exit 1
fi

echo ""
echo "✅ Setup complete!"






















