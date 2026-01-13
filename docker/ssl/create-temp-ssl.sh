#!/bin/bash

# This script creates temporary self-signed SSL certificates
# to allow Nginx to start, then obtains real Let's Encrypt certificates

set -e

DOMAIN="${SSL_DOMAIN:-coffeeglobe.sa}"
SSL_DIR="docker/nginx/ssl"
CERT_FILE="${SSL_DIR}/fullchain.pem"
KEY_FILE="${SSL_DIR}/privkey.pem"

echo "=== Creating temporary self-signed SSL certificates ==="

# Create SSL directory if it doesn't exist
mkdir -p "${SSL_DIR}"
echo "SSL directory: ${SSL_DIR}"

# Generate temporary self-signed certificate
echo "Generating self-signed certificate for ${DOMAIN}..."
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout "${KEY_FILE}" \
    -out "${CERT_FILE}" \
    -subj "/C=SA/ST=Riyadh/L=Riyadh/O=Coffee Globe/OU=IT/CN=${DOMAIN}/emailAddress=admin@${DOMAIN}" \
    2>/dev/null

# Set proper permissions
chmod 644 "${CERT_FILE}"
chmod 600 "${KEY_FILE}"

echo "✓ Temporary SSL certificates created successfully"
echo "  Certificate: ${CERT_FILE}"
echo "  Key: ${KEY_FILE}"
echo ""
echo "Now you can:"
echo "  1. Start Nginx: docker compose up -d nginx"
echo "  2. Get real certificates: make ssl-setup"
