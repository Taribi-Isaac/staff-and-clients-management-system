#!/bin/bash
# Script to fix route cache issues on the server

echo "🔧 Fixing route cache issues..."

cd ~/site_files

# Clear ALL caches
echo "🧹 Clearing all caches..."
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Remove compiled views that might have stale route references
echo "🗑️  Removing compiled views..."
rm -rf storage/framework/views/*

# Remove route cache file
echo "🗑️  Removing route cache..."
rm -f bootstrap/cache/routes-v7.php 2>/dev/null || true
rm -f bootstrap/cache/routes.php 2>/dev/null || true

# Rebuild caches
echo "⚡ Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify route exists
echo ""
echo "📋 Verifying dashboard route..."
php artisan route:list | grep dashboard || echo "⚠️  Dashboard route not found in route list"

echo ""
echo "✅ Route cache fix completed!"
echo ""
echo "If the issue persists, try:"
echo "1. Visit the site again"
echo "2. Check storage/logs/laravel.log for new errors"
echo ""


























