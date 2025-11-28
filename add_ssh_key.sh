#!/bin/bash
# Script to add SSH public key to server
# Run this script to copy your public key to the server

echo "Copy this public key and add it to your server:"
echo ""
cat ~/.ssh/github_actions_deploy.pub
echo ""
echo ""
echo "Then run these commands on your server:"
echo "  echo 'PASTE_PUBLIC_KEY_HERE' >> ~/.ssh/authorized_keys"
echo "  chmod 600 ~/.ssh/authorized_keys"
echo ""
echo "Or use this one-liner (replace with your actual public key):"
echo "  echo '$(cat ~/.ssh/github_actions_deploy.pub)' >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys"

