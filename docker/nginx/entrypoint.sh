#!/bin/sh
set -e

mkdir -p /etc/nginx/ssl /var/www/certbot /var/log/cron 2>/dev/null || true

chmod 755 /var/www/certbot 2>/dev/null || true

# Create temporary SSL certificates if they don't exist
if [ ! -f /etc/nginx/ssl/fullchain.pem ] || [ ! -f /etc/nginx/ssl/privkey.pem ]; then
    echo "Creating temporary SSL certificates..."
    openssl req -x509 -nodes -newkey rsa:2048 -days 365 \
        -keyout /etc/nginx/ssl/privkey.pem \
        -out /etc/nginx/ssl/fullchain.pem \
        -subj "/CN=coffeeglobe.sa" 2>/dev/null || true
    chmod 644 /etc/nginx/ssl/fullchain.pem 2>/dev/null || true
    chmod 600 /etc/nginx/ssl/privkey.pem 2>/dev/null || true
    echo "Temporary SSL certificates created"
fi

chmod +x /etc/letsencrypt/renewal-hooks/deploy/certbot-renewal.sh 2>/dev/null || true

crond -f -d 8 &

exec nginx -g 'daemon off;'
