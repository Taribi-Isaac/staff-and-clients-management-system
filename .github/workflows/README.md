# GitHub Actions Deployment

This directory contains the GitHub Actions workflow for automatic deployment to cPanel shared hosting.

## Workflow File

- `deploy.yml` - Main deployment workflow that runs on push to `main` branch

## How It Works

1. **Trigger**: Automatically runs when code is pushed to the `main` branch, or can be manually triggered via GitHub Actions UI
2. **Build**: Installs dependencies, builds assets (Vite), and prepares deployment package
3. **Deploy**: Transfers files to cPanel server via SSH
4. **Post-Deploy**: Runs Laravel optimization commands on the server

## Setup

See the main `DEPLOYMENT.md` file in the project root for detailed setup instructions.

## Required GitHub Secrets

Configure these in: **Settings** → **Secrets and variables** → **Actions**

- `CPANEL_HOST` - Your cPanel server hostname
- `CPANEL_USERNAME` - Your cPanel username (e.g., `raslorde`)
- `CPANEL_SSH_KEY` - Your private SSH key content
- `CPANEL_SSH_PORT` - SSH port (optional, defaults to 22)

## Manual Trigger

You can manually trigger the deployment by:
1. Going to **Actions** tab in GitHub
2. Selecting **Deploy to cPanel** workflow
3. Clicking **Run workflow**























