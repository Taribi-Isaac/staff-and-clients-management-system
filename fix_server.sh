#!/bin/bash
# Quick fix script to run on the server to resolve HTTP 500 errors

echo "🔧 Fixing Laravel application on server..."

cd ~/site_files

# 1. Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found. Creating from example..."
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "✅ Created .env file. Please edit it with your database credentials!"
    else
        echo "❌ .env.example not found. Please create .env manually."
    fi
fi

# 2. Ensure storage directories exist
echo "📁 Creating storage directories..."
mkdir -p storage/framework/{sessions,views,cache/data}
mkdir -p storage/logs
mkdir -p bootstrap/cache
touch storage/logs/laravel.log

# 3. Fix permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chmod -R 755 storage/framework
chmod 664 storage/logs/laravel.log
chown -R raslorde:raslorde storage bootstrap/cache 2>/dev/null || true

# 4. Install/update dependencies
echo "📦 Installing dependencies..."
if [ ! -d vendor ]; then
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# 5. Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan optimize:clear 2>/dev/null || true

# 6. Rebuild caches
echo "⚡ Rebuilding caches..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# 7. Fix public directory
echo "🌐 Fixing public directory..."
if [ ! -f ~/public_html/control.raslordeckltd.com/index.php ]; then
    cat > ~/public_html/control.raslordeckltd.com/index.php << 'INDEXEOF'
<?php
$laravelPath = __DIR__ . '/../../site_files';
if (!file_exists($laravelPath . '/public/index.php')) {
    die('Laravel application not found. Path: ' . $laravelPath);
}
chdir($laravelPath . '/public');
require $laravelPath . '/public/index.php';
INDEXEOF
    chmod 644 ~/public_html/control.raslordeckltd.com/index.php
fi

# 8. Check for errors
echo ""
echo "📋 Checking for errors..."
if [ -f storage/logs/laravel.log ]; then
    echo "Last 10 lines of laravel.log:"
    tail -10 storage/logs/laravel.log
fi

echo ""
echo "✅ Fix script completed!"
echo ""
echo "Next steps:"
echo "1. Edit .env file with correct database credentials"
echo "2. Check storage/logs/laravel.log for any errors"
echo "3. Visit https://control.raslordeckltd.com"
echo ""






















