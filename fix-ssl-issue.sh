#!/bin/bash

# Complete SSL fix script - fixes the nginx SSL issue and sets up Let's Encrypt

set -e

DOMAIN="${SSL_DOMAIN:-coffeeglobe.sa}"
EMAIL="${SSL_EMAIL:-admin@coffeeglobe.sa}"
NGINX_CONTAINER="coffee_globe_nginx"
SSL_DIR="docker/nginx/ssl"
CERT_FILE="${SSL_DIR}/fullchain.pem"
KEY_FILE="${SSL_DIR}/privkey.pem"

echo "=========================================="
echo "Coffee Globe SSL Fix Script"
echo "=========================================="
echo ""

# Step 1: Stop nginx if running (but restarting)
echo "Step 1: Stopping problematic nginx container..."
docker compose down nginx 2>/dev/null || true
docker rm -f ${NGINX_CONTAINER} 2>/dev/null || true
echo "✓ Container stopped"
echo ""

# Step 2: Create temporary self-signed certificates
echo "Step 2: Creating temporary self-signed SSL certificates..."
mkdir -p "${SSL_DIR}"

if [ ! -f "${CERT_FILE}" ] || [ ! -f "${KEY_FILE}" ]; then
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout "${KEY_FILE}" \
        -out "${CERT_FILE}" \
        -subj "/C=SA/ST=Riyadh/L=Riyadh/O=Coffee Globe/OU=IT/CN=${DOMAIN}/emailAddress=${EMAIL}" \
        2>/dev/null

    chmod 644 "${CERT_FILE}"
    chmod 600 "${KEY_FILE}"
    echo "✓ Temporary self-signed certificates created"
else
    echo "✓ SSL files already exist"
fi
echo ""

# Step 3: Start nginx with temporary certificates
echo "Step 3: Starting nginx with temporary certificates..."
docker compose up -d nginx
echo "Waiting for nginx to start..."
sleep 10

# Wait for nginx to be healthy
echo "Checking nginx status..."
for i in {1..30}; do
    if docker ps | grep -q "${NGINX_CONTAINER}" && docker exec ${NGINX_CONTAINER} nginx -t 2>/dev/null; then
        echo "✓ Nginx is running and healthy"
        break
    fi
    if [ $i -eq 30 ]; then
        echo "✗ Nginx failed to start properly"
        echo "Showing nginx logs:"
        docker logs ${NGINX_CONTAINER} --tail 30
        exit 1
    fi
    sleep 2
done
echo ""

# Step 4: Obtain Let's Encrypt certificates
echo "Step 4: Obtaining Let's Encrypt certificates..."
echo "This may take a few moments..."
echo ""

# Check if certbot is available in the container
if ! docker exec ${NGINX_CONTAINER} which certbot &>/dev/null; then
    echo "✗ Certbot is not installed in nginx container"
    echo "Please check the nginx Dockerfile"
    exit 1
fi

# Attempt to get certificates
echo "Requesting certificates for:"
echo "  - ${DOMAIN}"
echo "  - www.${DOMAIN}"
echo "  - coffeeglobe.com.sa"
echo "  - www.coffeeglobe.com.sa"
echo ""

if docker exec ${NGINX_CONTAINER} certbot certonly \
    --webroot \
    --webroot-path=/var/www/html/public \
    --email "${EMAIL}" \
    --agree-tos \
    --no-eff-email \
    --force-renewal \
    --non-interactive \
    -d "${DOMAIN}" \
    -d "www.${DOMAIN}" \
    -d "coffeeglobe.com.sa" \
    -d "www.coffeeglobe.com.sa" 2>&1; then
    
    echo "✓ Let's Encrypt certificates obtained successfully"
    echo ""
    
    # Copy certificates from container to host
    echo "Step 5: Copying certificates to host..."
    LETSENCRYPT_PATH="/etc/letsencrypt/live/${DOMAIN}"
    
    if docker exec ${NGINX_CONTAINER} test -f "${LETSENCRYPT_PATH}/fullchain.pem"; then
        docker cp "${NGINX_CONTAINER}:${LETSENCRYPT_PATH}/fullchain.pem" "${CERT_FILE}"
        docker cp "${NGINX_CONTAINER}:${LETSENCRYPT_PATH}/privkey.pem" "${KEY_FILE}"
        chmod 644 "${CERT_FILE}"
        chmod 600 "${KEY_FILE}"
        echo "✓ Certificates copied to ${SSL_DIR}"
    fi
    echo ""
    
    # Reload nginx
    echo "Step 6: Reloading nginx with new certificates..."
    if docker exec ${NGINX_CONTAINER} nginx -t; then
        docker exec ${NGINX_CONTAINER} nginx -s reload
        echo "✓ Nginx reloaded successfully"
    else
        echo "✗ Nginx configuration test failed"
        docker exec ${NGINX_CONTAINER} nginx -t 2>&1
        exit 1
    fi
    echo ""
    
    echo "=========================================="
    echo "✓ SSL Setup Complete!"
    echo "=========================================="
    echo ""
    echo "Your site should now be accessible via HTTPS:"
    echo "  - https://${DOMAIN}"
    echo "  - https://www.${DOMAIN}"
    echo ""
    echo "Certificate will auto-renew via cron job."
    echo ""
    
else
    echo "✗ Failed to obtain Let's Encrypt certificates"
    echo ""
    echo "Possible issues:"
    echo "  1. DNS not properly configured (domain must point to this server)"
    echo "  2. Port 80 not accessible from internet"
    echo "  3. Firewall blocking HTTP traffic"
    echo ""
    echo "However, your site is running with self-signed certificates."
    echo "You can manually retry with: make ssl-setup"
    echo ""
    echo "Showing certbot logs:"
    docker logs ${NGINX_CONTAINER} --tail 50 | grep -i certbot || true
    exit 1
fi
