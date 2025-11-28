# Troubleshooting HTTP 500 Error

## Common Causes and Solutions

### 1. Check Laravel Logs

SSH into your server and check the error logs:

```bash
ssh raslorde@131.153.147.50
cd ~/site_files
tail -50 storage/logs/laravel.log
```

### 2. Verify .env File Exists

```bash
cd ~/site_files
ls -la .env
```

If it doesn't exist, create it:

```bash
cp .env.example .env
# Then edit it with your database credentials
nano .env
```

### 3. Check Storage Permissions

```bash
cd ~/site_files
chmod -R 775 storage bootstrap/cache
chmod -R 755 storage/framework
chown -R raslorde:raslorde storage bootstrap/cache
```

### 4. Verify Database Connection

Check your `.env` file has correct database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=control_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

Test the connection:

```bash
cd ~/site_files
php artisan tinker
# Then try: DB::connection()->getPdo();
```

### 5. Clear All Caches

```bash
cd ~/site_files
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### 6. Check PHP Version

```bash
php -v
```

Should be PHP 8.2 or higher. If not, you may need to use a specific PHP version:

```bash
/opt/cpanel/ea-php82/root/usr/bin/php artisan config:clear
```

### 7. Verify Vendor Directory

```bash
cd ~/site_files
ls -la vendor/
```

If missing, run:

```bash
composer install --no-dev --optimize-autoloader
```

### 8. Check Public Directory Structure

```bash
ls -la ~/public_html/control.raslordeckltd.com/
```

Should contain:
- index.php
- .htaccess
- build/ (with manifest.json)

### 9. Check cPanel Error Logs

In cPanel, go to:
- **Metrics** → **Errors**
- Or check: `~/logs/error_log`

### 10. Verify Document Root

In cPanel:
1. Go to **Subdomains**
2. Check that `control.raslordeckltd.com` points to `public_html/control.raslordeckltd.com`

### 11. Test PHP Execution

Create a test file:

```bash
echo "<?php phpinfo(); ?>" > ~/public_html/control.raslordeckltd.com/test.php
```

Visit: `https://control.raslordeckltd.com/test.php`

If this doesn't work, there's a PHP configuration issue.

### 12. Check .htaccess File

Verify `.htaccess` exists in public directory:

```bash
cat ~/public_html/control.raslordeckltd.com/.htaccess
```

## Quick Fix Script

Run this on your server:

```bash
cd ~/site_files

# Ensure .env exists
if [ ! -f .env ]; then
  echo "Creating .env from example..."
  cp .env.example .env
  echo "⚠️  Please edit .env with your database credentials"
fi

# Fix permissions
chmod -R 775 storage bootstrap/cache
chmod -R 755 storage/framework
chown -R raslorde:raslorde storage bootstrap/cache

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify vendor
if [ ! -d vendor ]; then
  composer install --no-dev --optimize-autoloader
fi

echo "✅ Fixes applied. Check storage/logs/laravel.log for errors."
```

## Still Not Working?

1. Check the exact error in `storage/logs/laravel.log`
2. Verify all file paths are correct
3. Ensure database is accessible
4. Check PHP error display (temporarily enable in `.env`):
   ```env
   APP_DEBUG=true
   ```

