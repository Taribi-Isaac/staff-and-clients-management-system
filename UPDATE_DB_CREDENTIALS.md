# Updating Database Credentials

## Problem

The database user `raslorde_control` doesn't have access to `raslorde_admin` database. You need to update your `.env` file with the correct credentials.

## Solution

### Step 1: Find Your Database Credentials

You need to find the username and password for the `raslorde_admin` database. Check:

1. **cPanel → MySQL Databases**
   - Look for database users that have access to `raslorde_admin`
   - Common usernames: `raslorde_admin`, `raslorde`, or similar

2. **Or check your existing database configuration**
   - If you have access to phpMyAdmin, the username might be visible

### Step 2: Update .env File

```bash
cd ~/site_files
nano .env
```

Update these lines:

```env
DB_DATABASE=raslorde_admin
DB_USERNAME=raslorde_admin    # Change this to the correct username
DB_PASSWORD=your_password     # Change this to the correct password
```

**Common scenarios:**
- If the username is `raslorde_admin`, use that
- If the username is `raslorde`, use that
- The password should match what you set in cPanel

### Step 3: Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 4: Test Connection

```bash
php artisan tinker
```

Then:
```php
DB::connection()->getDatabaseName()  // Should show: raslorde_admin
DB::table('users')->count()  // Should work without errors
exit
```

## Alternative: Grant Access to Current User

If you want to keep using `raslorde_control` user, you can grant it access to `raslorde_admin`:

1. Go to **cPanel → MySQL Databases**
2. Find user `raslorde_control`
3. Add privileges to `raslorde_admin` database
4. Or create a new user specifically for `raslorde_admin`

## Quick Fix

If you know the username is `raslorde_admin`:

```bash
cd ~/site_files
sed -i 's/DB_USERNAME=raslorde_control/DB_USERNAME=raslorde_admin/' .env
# Then update password manually if needed
nano .env  # Edit DB_PASSWORD line
php artisan config:clear
```


























