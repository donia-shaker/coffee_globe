#!/bin/sh
set -e

mkdir -p /etc/nginx/ssl /var/www/certbot /var/log/cron 2>/dev/null || true

chmod 755 /var/www/certbot 2>/dev/null || true

chmod +x /etc/letsencrypt/renewal-hooks/deploy/certbot-renewal.sh 2>/dev/null || true

crond -f -d 8 &

exec nginx -g 'daemon off;'
