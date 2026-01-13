#!/bin/sh
set -e

# Create SSL directory if it doesn't exist
mkdir -p /etc/nginx/ssl 2>/dev/null || true

# Set permissions for certbot renewal hook
chmod +x /etc/letsencrypt/renewal-hooks/deploy/certbot-renewal.sh 2>/dev/null || true

# Start cron daemon for SSL renewal
crond -f -d 8 &

# Start nginx
exec nginx -g 'daemon off;'
