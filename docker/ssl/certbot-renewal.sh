#!/bin/sh

set -e

DOMAIN="${SSL_DOMAIN:-coffeeglobe.sa}"
SSL_TARGET_DIR="/var/www/html/docker/nginx/ssl"
LETSENCRYPT_SOURCE="/etc/letsencrypt/live/${DOMAIN}"

# Create SSL target directory if it doesn't exist
mkdir -p "${SSL_TARGET_DIR}" 2>/dev/null || true

# Copy renewed certificates to mounted volume
if [ -f "${LETSENCRYPT_SOURCE}/fullchain.pem" ] && [ -f "${LETSENCRYPT_SOURCE}/privkey.pem" ]; then
    cp "${LETSENCRYPT_SOURCE}/fullchain.pem" "${SSL_TARGET_DIR}/fullchain.pem" 2>/dev/null || true
    cp "${LETSENCRYPT_SOURCE}/privkey.pem" "${SSL_TARGET_DIR}/privkey.pem" 2>/dev/null || true
    chmod 644 "${SSL_TARGET_DIR}/fullchain.pem" 2>/dev/null || true
    chmod 600 "${SSL_TARGET_DIR}/privkey.pem" 2>/dev/null || true
    
    echo "$(date): Renewed SSL certificates copied to ${SSL_TARGET_DIR}" >> /var/log/cron/certbot-renew.log 2>&1 || true
else
    echo "$(date): Warning: Renewed certificates not found at ${LETSENCRYPT_SOURCE}" >> /var/log/cron/certbot-renew.log 2>&1 || true
fi

# Test nginx configuration before reloading
if nginx -t 2>/dev/null; then
    nginx -s reload 2>/dev/null || true
    echo "$(date): Nginx reloaded successfully after certificate renewal" >> /var/log/cron/certbot-renew.log 2>&1 || true
else
    echo "$(date): Error: Nginx configuration test failed after certificate renewal" >> /var/log/cron/certbot-renew.log 2>&1 || true
fi
