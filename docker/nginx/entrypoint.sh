#!/bin/sh
set -e

SSL_DIR="/etc/nginx/ssl"
CERT_FILE="${SSL_DIR}/fullchain.pem"
KEY_FILE="${SSL_DIR}/privkey.pem"

# Create SSL directory if it doesn't exist
mkdir -p "${SSL_DIR}" 2>/dev/null || true

# Generate self-signed certificate if not present
if [ ! -f "${CERT_FILE}" ] || [ ! -f "${KEY_FILE}" ]; then
    echo "Generating self-signed SSL certificate..."
    openssl req -x509 -nodes -days 365 \
        -newkey rsa:2048 \
        -keyout "${KEY_FILE}" \
        -out "${CERT_FILE}" \
        -subj "/CN=coffeeglobe.sa" 2>/dev/null || true
    chmod 644 "${CERT_FILE}" 2>/dev/null || true
    chmod 600 "${KEY_FILE}" 2>/dev/null || true
fi

# Set permissions for certbot renewal hook
chmod +x /etc/letsencrypt/renewal-hooks/deploy/certbot-renewal.sh 2>/dev/null || true

# Start cron daemon
crond -f -d 8 &

# Start nginx
exec nginx -g 'daemon off;'
