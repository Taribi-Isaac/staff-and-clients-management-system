# Deployment Guide

This project uses GitHub Actions to automatically deploy to cPanel shared hosting.

## Prerequisites

1. SSH access to your cPanel hosting
2. GitHub repository with Actions enabled
3. SSH key pair for authentication

## Setup Instructions

### 1. Generate SSH Key Pair (if you don't have one)

```bash
ssh-keygen -t rsa -b 4096 -C "github-actions-deploy" -f ~/.ssh/github_actions_deploy
```

This creates two files:
- `~/.ssh/github_actions_deploy` (private key - keep secret!)
- `~/.ssh/github_actions_deploy.pub` (public key)

### 2. Add Public Key to cPanel Server

Copy the public key content:
```bash
cat ~/.ssh/github_actions_deploy.pub
```

Then add it to your cPanel server:
1. Log into cPanel
2. Go to **SSH Access** or **SSH/Shell Access**
3. Add the public key to authorized_keys

Or via SSH:
```bash
ssh raslorde@your-server.com
mkdir -p ~/.ssh
chmod 700 ~/.ssh
echo "YOUR_PUBLIC_KEY_CONTENT" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

### 3. Configure GitHub Secrets

Go to your GitHub repository:
1. Navigate to **Settings** → **Secrets and variables** → **Actions**
2. Click **New repository secret**
3. Add the following secrets:

#### Required Secrets:

- **CPANEL_HOST**: Your cPanel server hostname (e.g., `wghp3.yourhost.com` or IP address)
- **CPANEL_USERNAME**: Your cPanel username (e.g., `raslorde`)
- **CPANEL_SSH_KEY**: The content of your **private key** (`github_actions_deploy`)
- **CPANEL_SSH_PORT**: SSH port (usually `22`, optional - defaults to 22)

### 4. Test SSH Connection

Test your SSH connection from your local machine:
```bash
ssh -i ~/.ssh/github_actions_deploy raslorde@your-server.com
```

### 5. Verify Server Paths

The deployment script assumes:
- Application files: `~/site_files`
- Public files: `~/public_html/control.raslordeckltd.com`

If your paths are different, update `.github/workflows/deploy.yml` accordingly.

## Deployment Process

The GitHub Actions workflow will:

1. **Checkout code** from the repository
2. **Install dependencies** (Composer and NPM)
3. **Build assets** (Vite build)
4. **Deploy files** to `~/site_files`
5. **Run deployment commands**:
   - Install/update Composer dependencies
   - Clear caches
   - Optimize for production
   - Set proper permissions
6. **Deploy public files** to `~/public_html/control.raslordeckltd.com`

## Manual Deployment

If you need to deploy manually:

```bash
# On your local machine
cd /path/to/project

# Build assets
npm run build

# Create deployment package
rsync -av --exclude='.git' \
  --exclude='node_modules' \
  --exclude='.env' \
  --exclude='storage' \
  . raslorde@your-server.com:~/site_files/

# SSH into server
ssh raslorde@your-server.com
cd ~/site_files

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations (if needed)
php artisan migrate --force

# Clear and cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
```

## Important Notes

1. **Environment File**: The `.env` file is NOT deployed. Make sure it exists on the server with correct configuration.

2. **Storage Directory**: The `storage` directory is excluded from deployment. Ensure it exists on the server with proper permissions.

3. **Database Migrations**: Migrations are commented out in the workflow. Uncomment if you want automatic migrations on deploy.

4. **Backup**: Always backup your production database before deploying.

5. **Testing**: Test the deployment on a staging environment first if possible.

## Troubleshooting

### SSH Connection Issues
- Verify SSH key is correctly added to server
- Check SSH port (some hosts use non-standard ports)
- Ensure username is correct

### Permission Errors
- Run: `chmod -R 755 storage bootstrap/cache`
- Check file ownership: `chown -R raslorde:raslorde storage bootstrap/cache`

### Deployment Fails
- Check GitHub Actions logs for specific errors
- Verify all secrets are correctly set
- Ensure server paths are correct
- Check disk space on server

### Assets Not Loading
- Ensure `npm run build` completed successfully
- Verify `public/build` directory exists
- Check file permissions on public files

## Security Best Practices

1. **Never commit** `.env` file or private keys
2. **Use strong SSH keys** (4096-bit RSA minimum)
3. **Restrict SSH access** to specific IPs if possible
4. **Rotate SSH keys** periodically
5. **Monitor deployment logs** for suspicious activity

