# Switching to raslorde_admin Database

This guide helps you switch from `raslorde_controlDB` to `raslorde_admin` database.

## Why Switch?

The `raslorde_admin` database contains all your old/complete data, while `raslorde_controlDB` is missing some data.

## Steps to Switch

### 1. Update .env File

On your server, edit the `.env` file:

```bash
cd ~/site_files
nano .env
```

Change this line:
```env
DB_DATABASE=raslorde_controlDB
```

To:
```env
DB_DATABASE=raslorde_admin
```

Also verify these settings match your `raslorde_admin` database:
```env
DB_USERNAME=raslorde_admin  # or whatever your username is
DB_PASSWORD=your_password
```

### 2. Create Invoice Tables in raslorde_admin

Run the setup script:

```bash
cd ~/site_files
php create_tables_in_admin_db.php
```

This script will:
- Create `invoices` table (if it doesn't exist)
- Create `invoice_items` table (if it doesn't exist)
- Mark migrations as run

### 3. Clear Config Cache

```bash
php artisan config:clear
php artisan config:cache
```

### 4. Verify

```bash
php artisan tinker
```

Then in tinker:
```php
DB::connection()->getDatabaseName()  // Should show: raslorde_admin
Schema::hasTable('invoices')  // Should return: true
Schema::hasTable('invoice_items')  // Should return: true
exit
```

## Alternative: Manual SQL in phpMyAdmin

If you prefer to use phpMyAdmin:

1. Select `raslorde_admin` database
2. Go to SQL tab
3. Run the SQL from `create_invoice_items_simple.sql` (but make sure you're in the right database!)

## After Switching

- All your old data will be accessible
- Invoice functionality will work with your complete dataset
- Make sure to update your local `.env` if you want to match production

## Rollback

If you need to switch back:

```bash
cd ~/site_files
# Restore from backup
cp .env.backup.* .env
# Or manually edit .env to change DB_DATABASE back
php artisan config:clear
php artisan config:cache
```























