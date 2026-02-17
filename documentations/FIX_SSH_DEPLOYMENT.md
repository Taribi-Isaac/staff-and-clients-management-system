# Fix SSH Deployment Connection Issues

## Immediate Actions to Resolve "connection reset by peer" Error

### 1. **Verify GitHub Secrets**

Check that all secrets are correctly set in GitHub:

1. Go to: Repository → Settings → Secrets and variables → Actions
2. Verify these secrets exist and are correct:

   **CPANEL_HOST**
   - Should be your server hostname (e.g., `wghp3.yourhost.com`) or IP address
   - NOT a URL like `https://...`
   - Example: `192.168.1.100` or `server.example.com`

   **CPANEL_USERNAME**
   - Your cPanel username (usually `raslorde` in your case)
   - Case-sensitive

   **CPANEL_SSH_KEY**
   - The FULL private key content
   - Must include BEGIN and END lines
   - No extra spaces or line breaks
   - Format:
     ```
     -----BEGIN OPENSSH PRIVATE KEY-----
     [key content]
     -----END OPENSSH PRIVATE KEY-----
     ```

   **CPANEL_SSH_PORT** (Optional but recommended)
   - Usually `22` for standard SSH
   - Could be `2222` for some cPanel servers
   - Check your cPanel SSH settings

### 2. **Test SSH Connection Locally**

Before deploying, test the connection from your local machine:

```bash
# Generate key if you haven't already
ssh-keygen -t rsa -b 4096 -C "github-actions-deploy" -f ~/.ssh/github_actions_deploy

# Test connection
ssh -i ~/.ssh/github_actions_deploy -p 22 raslorde@YOUR_SERVER_HOSTNAME

# If this fails, the issue is with the key or server, not GitHub Actions
```

### 3. **Verify Public Key on Server**

SSH into your server and verify the public key is authorized:

```bash
# SSH into server
ssh raslorde@your-server.com

# Check authorized_keys
cat ~/.ssh/authorized_keys

# Your public key should be listed
# If not, add it:
cat ~/.ssh/github_actions_deploy.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh
```

### 4. **Check Server Firewall**

The most common cause is firewall blocking GitHub Actions IPs.

**Option A: Whitelist GitHub Actions IPs**

1. Get GitHub Actions IP ranges:
   ```bash
   curl https://api.github.com/meta | grep actions
   ```

2. Add these IPs to your server's firewall whitelist

**Option B: Temporarily Disable Firewall (for testing)**

```bash
# On server (as root or with sudo)
# For cPanel/WHM:
/usr/local/cpanel/scripts/disable_firewall

# Or contact hosting provider to whitelist GitHub Actions IPs
```

### 5. **Check SSH Server Configuration**

Some servers have strict SSH settings. Check these:

```bash
# On server, check SSH config
sudo cat /etc/ssh/sshd_config | grep -E "MaxStartups|MaxSessions|PermitRootLogin"

# Common issues:
# - MaxStartups too low (should be at least 10)
# - PasswordAuthentication disabled (should be fine if using keys)
```

### 6. **Alternative: Use Password Authentication (Temporary)**

If key authentication continues to fail, you can temporarily use password:

1. Add secret: `CPANEL_PASSWORD` with your cPanel password
2. Update workflow to use password instead of key (see alternative workflow below)

**⚠️ WARNING**: Password authentication is less secure. Use only for testing.

### 7. **Check cPanel SSH Access Settings**

1. Log into cPanel
2. Go to **Security** → **SSH Access**
3. Ensure:
   - SSH access is enabled
   - No IP restrictions blocking GitHub Actions
   - Key-based authentication is allowed

### 8. **Verify SSH Port**

Some hosting providers use non-standard SSH ports:

```bash
# Test different ports
ssh -p 22 raslorde@your-server.com
ssh -p 2222 raslorde@your-server.com
ssh -p 2200 raslorde@your-server.com

# Update CPANEL_SSH_PORT secret with the working port
```

## Updated Workflow Features

The workflow has been updated with:
- ✅ SSH connection test before deployment
- ✅ Increased timeouts (300s for file transfers, 60s for commands)
- ✅ Debug mode enabled for detailed logs
- ✅ Better error messages

## Next Steps

1. **Check the "Test SSH Connection" step** in GitHub Actions
   - This will show if basic SSH works
   - If this fails, the issue is authentication/network
   - If this passes but SCP fails, the issue is with file transfer

2. **Review workflow logs** for the exact error
   - Look at the "Deploy to cPanel via SSH" step
   - Check for specific error messages
   - Debug mode will show connection details

3. **Contact Hosting Provider** if issues persist
   - Ask them to whitelist GitHub Actions IP ranges
   - Request SSH server logs
   - Verify SSH access is enabled for your account

## Alternative Deployment Methods

If SSH continues to fail, consider:

1. **FTP/SFTP Deployment** (if available)
2. **Git-based Deployment** (if server has Git)
3. **Manual Deployment** (upload files via cPanel File Manager)
4. **Deployment via cPanel API** (if available)

## Quick Checklist

- [ ] SSH key correctly formatted in GitHub Secrets
- [ ] Public key added to server's `~/.ssh/authorized_keys`
- [ ] Server firewall allows GitHub Actions IPs
- [ ] SSH port is correct (check cPanel settings)
- [ ] SSH access enabled in cPanel
- [ ] Tested SSH connection locally
- [ ] Server not blocking connections from GitHub Actions IPs

## Most Likely Fix

**90% of cases**: The server firewall is blocking GitHub Actions IPs. Contact your hosting provider to whitelist GitHub Actions IP ranges.

You can get the IP ranges here: https://api.github.com/meta (look for the `actions` array)
