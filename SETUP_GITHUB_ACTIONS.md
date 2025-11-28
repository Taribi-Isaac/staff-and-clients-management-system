# Quick Setup Guide for GitHub Actions Deployment

## Step-by-Step Setup

### 1. Generate SSH Key (One-time setup)

```bash
# Generate SSH key pair
ssh-keygen -t rsa -b 4096 -C "github-actions-deploy" -f ~/.ssh/github_actions_deploy

# View the public key (you'll need to add this to your server)
cat ~/.ssh/github_actions_deploy.pub

# View the private key (you'll add this to GitHub secrets)
cat ~/.ssh/github_actions_deploy
```

### 2. Add Public Key to cPanel Server

**Option A: Via cPanel Interface**
1. Log into cPanel
2. Go to **SSH Access** or **SSH/Shell Access**
3. Click **Manage SSH Keys**
4. Click **Import Key**
5. Paste your public key content
6. Click **Import**
7. Click **Authorize** next to the imported key

**Option B: Via SSH**
```bash
# SSH into your server
ssh raslorde@your-server.com

# Add public key to authorized_keys
mkdir -p ~/.ssh
chmod 700 ~/.ssh
echo "YOUR_PUBLIC_KEY_CONTENT_HERE" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

### 3. Add Secrets to GitHub

1. Go to your repository: https://github.com/Taribi-Isaac/staff-and-clients-management-system
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret** for each:

   **CPANEL_HOST**
   - Value: Your server hostname (e.g., `wghp3.yourhost.com` or IP address)
   - Find this in your cPanel or hosting provider's documentation

   **CPANEL_USERNAME**
   - Value: `raslorde` (your cPanel username)

   **CPANEL_SSH_KEY**
   - Value: The entire content of `~/.ssh/github_actions_deploy` (the private key)
   - Copy everything including `-----BEGIN OPENSSH PRIVATE KEY-----` and `-----END OPENSSH PRIVATE KEY-----`

   **CPANEL_SSH_PORT** (Optional)
   - Value: `22` (or your custom SSH port if different)

### 4. Test the Connection

Test SSH connection from your local machine:
```bash
ssh -i ~/.ssh/github_actions_deploy raslorde@your-server-hostname
```

If it connects without asking for a password, you're good to go!

### 5. Push to GitHub and Deploy

```bash
# Commit and push the workflow file
git add .github/workflows/deploy.yml
git commit -m "Add GitHub Actions deployment workflow"
git push origin main
```

The deployment will automatically start when you push to the `main` branch.

### 6. Monitor Deployment

1. Go to your GitHub repository
2. Click the **Actions** tab
3. You'll see the deployment workflow running
4. Click on it to see real-time logs

## Finding Your Server Hostname

If you're not sure about your server hostname:

1. **Check cPanel**: Look at the server information in cPanel
2. **Check SSH logs**: When you SSH in, it usually shows the hostname
3. **Ask your hosting provider**: They can provide the exact hostname
4. **Use IP address**: You can use the server's IP address instead

## Troubleshooting

### "Permission denied (publickey)" error
- Verify the public key is in `~/.ssh/authorized_keys` on the server
- Check file permissions: `chmod 600 ~/.ssh/authorized_keys`
- Ensure the private key in GitHub secrets is correct

### "Host key verification failed"
- This usually means the hostname is incorrect
- Try using the IP address instead
- Or add the host to known_hosts manually

### "No such file or directory" errors
- Verify the paths in the workflow match your server structure
- Default paths are:
  - Application: `~/site_files`
  - Public: `~/public_html/control.raslordeckltd.com`

### Deployment succeeds but site doesn't work
- Check `.env` file exists on server with correct values
- Verify storage permissions: `chmod -R 755 storage bootstrap/cache`
- Check Laravel logs: `storage/logs/laravel.log`

## Important Notes

⚠️ **Never commit your `.env` file or private SSH keys to Git!**

✅ The workflow automatically:
- Builds your assets (Vite)
- Installs dependencies
- Clears and caches Laravel config
- Sets proper permissions

❌ The workflow does NOT:
- Run database migrations (commented out for safety)
- Deploy `.env` file (you must manage this separately)
- Deploy `storage` directory (preserved on server)

## Next Steps

After successful setup:
1. Test a deployment by making a small change and pushing
2. Monitor the first few deployments to ensure everything works
3. Consider enabling automatic migrations if desired (uncomment in workflow)
4. Set up email notifications for deployment status (optional)

