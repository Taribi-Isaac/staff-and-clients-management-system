# SSH Connection Troubleshooting Guide

## Error: "ssh: handshake failed: read tcp ...: read: connection reset by peer"

This error indicates the SSH connection is being reset during the handshake phase. Here are solutions:

## Common Causes & Solutions

### 1. **Firewall Blocking GitHub Actions IPs**

**Problem**: Your server firewall may be blocking connections from GitHub Actions IP ranges.

**Solution**:
- Contact your hosting provider to whitelist GitHub Actions IP ranges
- Or temporarily disable firewall for testing
- GitHub Actions IP ranges: https://api.github.com/meta (look for `actions` IPs)

**Quick Fix**: Add GitHub Actions IPs to your server's firewall whitelist.

### 2. **SSH Key Format Issues**

**Problem**: The SSH key in GitHub secrets might be incorrectly formatted.

**Solution**:
1. Verify the key format in GitHub Secrets:
   - Should start with `-----BEGIN OPENSSH PRIVATE KEY-----` or `-----BEGIN RSA PRIVATE KEY-----`
   - Should end with `-----END OPENSSH PRIVATE KEY-----` or `-----END RSA PRIVATE KEY-----`
   - Should include all lines (no missing parts)
   - No extra spaces or characters

2. Regenerate key if needed:
   ```bash
   ssh-keygen -t rsa -b 4096 -C "github-actions-deploy" -f ~/.ssh/github_actions_deploy
   ```

### 3. **SSH Port Issues**

**Problem**: Wrong SSH port or port blocked.

**Solution**:
- Verify SSH port in cPanel or hosting settings
- Common ports: 22 (standard), 2222 (cPanel alternative)
- Check if port is open: `telnet your-server.com 22`
- Update `CPANEL_SSH_PORT` secret if different from 22

### 4. **Server SSH Configuration**

**Problem**: SSH server may have strict security settings.

**Solution**:
- Check SSH server logs: `/var/log/auth.log` or `/var/log/secure`
- Verify `MaxStartups` and `MaxSessions` settings
- Check if server allows passwordless key authentication

### 5. **Network Timeout Issues**

**Problem**: Connection timeout during handshake.

**Solution** (Already added to workflow):
- Increased timeout settings
- Added retry logic
- Added connection test step

### 6. **SSH Key Not Authorized on Server**

**Problem**: Public key not properly added to `authorized_keys`.

**Solution**:
```bash
# SSH into server manually
ssh raslorde@your-server.com

# Check authorized_keys
cat ~/.ssh/authorized_keys

# Verify your public key is there
# If not, add it:
echo "YOUR_PUBLIC_KEY" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh
```

### 7. **cPanel SSH Access Restrictions**

**Problem**: cPanel may restrict SSH access.

**Solution**:
1. Log into cPanel
2. Go to **Security** → **SSH Access**
3. Ensure SSH access is enabled
4. Check if there are IP restrictions
5. Temporarily allow all IPs for testing

### 8. **Server Overload**

**Problem**: Server might be rejecting connections due to load.

**Solution**:
- Check server resources (CPU, memory)
- Wait a few minutes and retry
- Contact hosting provider if persistent

## Testing Steps

### Step 1: Test SSH Connection Locally

```bash
# Test with the same key
ssh -i ~/.ssh/github_actions_deploy -p 22 raslorde@your-server-hostname

# If this works, the issue is with GitHub Actions environment
# If this fails, the issue is with the key or server configuration
```

### Step 2: Verify GitHub Secrets

1. Go to GitHub repository → Settings → Secrets and variables → Actions
2. Verify all secrets are set:
   - `CPANEL_HOST` - Server hostname or IP
   - `CPANEL_USERNAME` - SSH username (usually cPanel username)
   - `CPANEL_SSH_KEY` - Full private key content
   - `CPANEL_SSH_PORT` - SSH port (usually 22)

### Step 3: Check Workflow Logs

1. Go to GitHub Actions tab
2. Click on the failed workflow
3. Expand the "Deploy to cPanel via SSH" step
4. Look for detailed error messages
5. Check the "Test SSH Connection" step output

### Step 4: Enable Debug Mode

The workflow now includes `debug: true` which will show more detailed connection information.

## Alternative: Use SFTP Instead of SCP

If SCP continues to fail, we can switch to SFTP:

```yaml
- name: Deploy via SFTP
  uses: SamKirkland/FTP-Deploy-Action@4.3.0
  with:
    server: ${{ secrets.CPANEL_HOST }}
    username: ${{ secrets.CPANEL_USERNAME }}
    password: ${{ secrets.CPANEL_PASSWORD }}
    server-dir: /home/raslorde/site_files/
```

## Alternative: Use rsync via SSH

```yaml
- name: Deploy via rsync
  run: |
    rsync -avz -e "ssh -i ${{ secrets.CPANEL_SSH_KEY }}" \
      deploy/ raslorde@${{ secrets.CPANEL_HOST }}:~/site_files/
```

## Contact Hosting Provider

If none of the above works, contact your hosting provider with:
1. The error message
2. GitHub Actions IP ranges that need whitelisting
3. Request to check SSH server logs
4. Request to verify SSH access is enabled for your account

## Quick Fix: Update Workflow with Better Error Handling

The workflow has been updated with:
- Connection test step before deployment
- Increased timeouts
- Debug mode enabled
- Better error messages

Try deploying again and check the "Test SSH Connection" step output for more details.
