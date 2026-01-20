# Fix Mail Configuration for Password Reset

## Problem

The mail configuration is using port 456 which doesn't work. The error shows:
```
Connection refused with host "raslordeckltd.com:456"
```

## Solution

Update your `.env` file on the server with correct SMTP settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=raslordeckltd.com
MAIL_PORT=465
MAIL_USERNAME=admin@raslordeckltd.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="admin@raslordeckltd.com"
MAIL_FROM_NAME="Raslordeck Limited"
```

## Common SMTP Ports

- **Port 465**: SSL/TLS encryption (recommended for production)
- **Port 587**: STARTTLS encryption (alternative)
- **Port 25**: Unencrypted (not recommended)

## Steps to Fix

1. **Edit .env file on server:**
```bash
cd ~/site_files
nano .env
```

2. **Update mail settings:**
```env
MAIL_MAILER=smtp
MAIL_HOST=raslordeckltd.com
MAIL_PORT=465
MAIL_USERNAME=admin@raslordeckltd.com
MAIL_PASSWORD=your_actual_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="admin@raslordeckltd.com"
MAIL_FROM_NAME="${APP_NAME}"
```

3. **Clear config cache:**
```bash
php artisan config:clear
php artisan config:cache
```

4. **Test mail:**
```bash
php artisan tinker
```

Then in tinker:
```php
Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});
```

## Alternative: Use cPanel Email

If you have cPanel email setup, you can use:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.raslordeckltd.com
MAIL_PORT=465
MAIL_USERNAME=admin@raslordeckltd.com
MAIL_PASSWORD=your_cpanel_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="admin@raslordeckltd.com"
MAIL_FROM_NAME="Raslordeck Limited"
```

## Troubleshooting

- **Port 465 with SSL**: Most reliable, works with most email providers
- **Port 587 with TLS**: Alternative if 465 doesn't work
- **Check firewall**: Make sure ports are not blocked
- **Verify credentials**: Ensure username/password are correct


























