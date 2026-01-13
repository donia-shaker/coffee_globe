#!/bin/bash

set -e

DOMAIN="${SSL_DOMAIN:-coffeeglobe.sa}"
NGINX_CONTAINER="coffee_globe_nginx"
SSL_DIR="docker/nginx/ssl"
CERT_FILE="${SSL_DIR}/fullchain.pem"
KEY_FILE="${SSL_DIR}/privkey.pem"
LETSENCRYPT_PATH="/etc/letsencrypt/live/${DOMAIN}"

if ! docker ps | grep -q "${NGINX_CONTAINER}"; then
    echo "Error: Nginx container is not running. Please start containers first: make up"
    exit 1
fi

echo "Checking SSL certificate renewal..."

# Test renewal with dry-run
if docker exec "${NGINX_CONTAINER}" certbot renew --quiet --no-self-upgrade --dry-run &> /dev/null; then
    # Check if renewal is needed
    RENEWAL_OUTPUT=$(docker exec "${NGINX_CONTAINER}" certbot renew --dry-run 2>&1 || true)
    
    if echo "${RENEWAL_OUTPUT}" | grep -q "would be renewed"; then
        echo "Certificate renewal is needed. Renewing..."
        
        # Perform actual renewal
        if docker exec "${NGINX_CONTAINER}" certbot renew --quiet --no-self-upgrade --no-random-sleep-on-renew; then
            echo "Certificates renewed successfully"
            
            # Copy renewed certificates to host
            if docker exec "${NGINX_CONTAINER}" test -f "${LETSENCRYPT_PATH}/fullchain.pem"; then
                docker cp "${NGINX_CONTAINER}:${LETSENCRYPT_PATH}/fullchain.pem" "${CERT_FILE}"
                docker cp "${NGINX_CONTAINER}:${LETSENCRYPT_PATH}/privkey.pem" "${KEY_FILE}"
                chmod 644 "${CERT_FILE}" 2>/dev/null || true
                chmod 600 "${KEY_FILE}" 2>/dev/null || true
                echo "Certificates copied to ${SSL_DIR}"
            else
                echo "Warning: Renewed certificates not found at expected path"
            fi
            
            # Test nginx configuration and reload
            if docker exec "${NGINX_CONTAINER}" nginx -t; then
                docker exec "${NGINX_CONTAINER}" nginx -s reload
                echo "SSL certificates renewed and nginx reloaded successfully"
            else
                echo "Error: Nginx configuration test failed after renewal"
                exit 1
            fi
        else
            echo "Error: Failed to renew certificates"
            exit 1
        fi
    else
        echo "No renewal needed. Certificates are up to date."
    fi
else
    echo "Warning: Dry-run failed. Attempting renewal anyway..."
    
    # Attempt renewal
    if docker exec "${NGINX_CONTAINER}" certbot renew --quiet --no-self-upgrade --no-random-sleep-on-renew; then
        # Copy certificates if renewal succeeded
        if docker exec "${NGINX_CONTAINER}" test -f "${LETSENCRYPT_PATH}/fullchain.pem"; then
            docker cp "${NGINX_CONTAINER}:${LETSENCRYPT_PATH}/fullchain.pem" "${CERT_FILE}" 2>/dev/null || true
            docker cp "${NGINX_CONTAINER}:${LETSENCRYPT_PATH}/privkey.pem" "${KEY_FILE}" 2>/dev/null || true
            chmod 644 "${CERT_FILE}" 2>/dev/null || true
            chmod 600 "${KEY_FILE}" 2>/dev/null || true
        fi
        
        # Reload nginx if configuration is valid
        if docker exec "${NGINX_CONTAINER}" nginx -t &> /dev/null; then
            docker exec "${NGINX_CONTAINER}" nginx -s reload 2>/dev/null || true
        fi
    else
        echo "Error: Certificate renewal failed"
        exit 1
    fi
fi
