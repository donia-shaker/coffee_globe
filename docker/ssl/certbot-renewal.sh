#!/bin/sh

set -e

DOMAIN="${SSL_DOMAIN:-coffeeglobe.sa}"
SSL_TARGET_DIR="/var/www/html/docker/nginx/ssl"

mkdir -p "${SSL_TARGET_DIR}" 2>/dev/null || true

if [ -f "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" ] && [ -f "/etc/letsencrypt/live/${DOMAIN}/privkey.pem" ]; then
    cp /etc/letsencrypt/live/${DOMAIN}/fullchain.pem "${SSL_TARGET_DIR}/fullchain.pem" 2>/dev/null || true
    cp /etc/letsencrypt/live/${DOMAIN}/privkey.pem "${SSL_TARGET_DIR}/privkey.pem" 2>/dev/null || true
    chmod 644 "${SSL_TARGET_DIR}/fullchain.pem" 2>/dev/null || true
    chmod 600 "${SSL_TARGET_DIR}/privkey.pem" 2>/dev/null || true
fi

nginx -s reload 2>/dev/null || true

echo "$(date): SSL certificate renewal hook executed" >> /var/log/cron/certbot-renew.log 2>&1 || true
