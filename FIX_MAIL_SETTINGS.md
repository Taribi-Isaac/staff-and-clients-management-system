# Fix Mail Configuration

## Problem

Password reset emails are failing with:
```
Connection refused with host "raslordeckltd.com:456"
```

Port 456 is incorrect for SMTP. Standard ports are:
- **465** - SSL/TLS (recommended)
- **587** - STARTTLS
- **25** - Unencrypted (not recommended)

## Solution

Update your `.env` file on the server:

```bash
cd ~/site_files
nano .env
```

Change the mail settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.raslordeckltd.com
MAIL_PORT=465
MAIL_USERNAME=admin@raslordeckltd.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="admin@raslordeckltd.com"
MAIL_FROM_NAME="Raslordeck Limited"
```

**Important changes:**
- `MAIL_PORT`: Change from `456` to `465` (SSL) or `587` (TLS)
- `MAIL_HOST`: Use `mail.raslordeckltd.com` (cPanel mail server) instead of `raslordeckltd.com`
- `MAIL_ENCRYPTION`: Set to `ssl` (for port 465) or `tls` (for port 587)

## After Updating

```bash
php artisan config:clear
php artisan config:cache
```

## Test Email

```bash
php artisan tinker
```

Then:
```php
Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});
```

## Alternative Ports

If port 465 doesn't work, try:
- Port **587** with `MAIL_ENCRYPTION=tls`
- Or check your cPanel email settings for the correct port

