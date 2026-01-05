#!/bin/bash
# Script to update database credentials for raslorde_admin

echo "🔧 Updating database credentials for raslorde_admin..."

cd ~/site_files

# Show current settings
echo "📋 Current database settings:"
echo "Database: $(grep '^DB_DATABASE=' .env | cut -d'=' -f2)"
echo "Username: $(grep '^DB_USERNAME=' .env | cut -d'=' -f2)"

echo ""
echo "⚠️  You need to update DB_USERNAME and DB_PASSWORD for raslorde_admin"
echo ""
echo "Common usernames for raslorde_admin database:"
echo "  - raslorde_admin"
echo "  - raslorde"
echo "  - (check your cPanel database users)"
echo ""
echo "To update manually, run:"
echo "  nano .env"
echo ""
echo "Then change:"
echo "  DB_USERNAME=raslorde_control"
echo "  To:"
echo "  DB_USERNAME=raslorde_admin  (or your actual username)"
echo ""
echo "And update DB_PASSWORD if needed"






















