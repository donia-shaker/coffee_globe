#!/bin/sh

set -e

DOMAIN="${SSL_DOMAIN:-coffeeglobe.sa}"
SSL_TARGET_DIR="/var/www/html/docker/nginx/ssl"
LETSENCRYPT_SOURCE="/etc/letsencrypt/live/${DOMAIN}"

mkdir -p "${SSL_TARGET_DIR}" /var/log/cron 2>/dev/null || true

if [ -f "${LETSENCRYPT_SOURCE}/fullchain.pem" ] && [ -f "${LETSENCRYPT_SOURCE}/privkey.pem" ]; then
    cat "${LETSENCRYPT_SOURCE}/fullchain.pem" > "${SSL_TARGET_DIR}/fullchain.pem" 2>/dev/null || true
    cat "${LETSENCRYPT_SOURCE}/privkey.pem" > "${SSL_TARGET_DIR}/privkey.pem" 2>/dev/null || true
    
    chmod 644 "${SSL_TARGET_DIR}/fullchain.pem" 2>/dev/null || true
    chmod 600 "${SSL_TARGET_DIR}/privkey.pem" 2>/dev/null || true
    
    echo "$(date): SSL certificates renewed and copied successfully" >> /var/log/cron/certbot-renew.log 2>&1 || true
    
    if nginx -t 2>/dev/null; then
        nginx -s reload 2>/dev/null || true
        echo "$(date): Nginx reloaded successfully" >> /var/log/cron/certbot-renew.log 2>&1 || true
    else
        echo "$(date): ERROR: Nginx config test failed" >> /var/log/cron/certbot-renew.log 2>&1 || true
    fi
else
    echo "$(date): ERROR: Certificates not found at ${LETSENCRYPT_SOURCE}" >> /var/log/cron/certbot-renew.log 2>&1 || true
fi
