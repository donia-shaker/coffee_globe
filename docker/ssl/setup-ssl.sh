#!/bin/bash

set -e

DOMAIN="${SSL_DOMAIN:-coffeeglobe.sa}"
EMAIL="${SSL_EMAIL:-admin@coffeeglobe.sa}"
NGINX_CONTAINER="coffee_globe_nginx"
SSL_DIR="docker/nginx/ssl"
CERT_FILE="${SSL_DIR}/fullchain.pem"
KEY_FILE="${SSL_DIR}/privkey.pem"
LETSENCRYPT_PATH="/etc/letsencrypt/live/${DOMAIN}"

# Create SSL directory if it doesn't exist
if [ ! -d "${SSL_DIR}" ]; then
    mkdir -p "${SSL_DIR}"
    echo "Created SSL directory: ${SSL_DIR}"
fi

# Check if containers are running, start them if not
if ! docker ps | grep -q "${NGINX_CONTAINER}"; then
    echo "Nginx container is not running. Starting containers..."
    if command -v docker-compose &> /dev/null; then
        docker-compose up -d
    elif command -v docker &> /dev/null && docker compose version &> /dev/null; then
        docker compose up -d
    else
        echo "Error: Docker Compose is not available. Please start containers manually: make up"
        exit 1
    fi
    
    echo "Waiting for containers to be ready..."
    sleep 15
    
    # Wait for nginx to be healthy
    for i in {1..30}; do
        if docker ps | grep -q "${NGINX_CONTAINER}"; then
            if docker exec "${NGINX_CONTAINER}" nginx -t &> /dev/null; then
                break
            fi
        fi
        sleep 2
    done
fi

# Check if certificates already exist
if [ -f "${CERT_FILE}" ] && [ -f "${KEY_FILE}" ]; then
    echo "SSL certificates already exist. Checking certificate type..."
    
    IS_SELF_SIGNED=false
    IS_LETSENCRYPT=false
    
    if command -v openssl &> /dev/null; then
        # Check if certificate is self-signed or Let's Encrypt
        CERT_ISSUER=$(openssl x509 -in "${CERT_FILE}" -noout -issuer 2>/dev/null | grep -i "Let's Encrypt\|Let's Encrypt Authority" || echo "")
        CERT_SUBJECT=$(openssl x509 -in "${CERT_FILE}" -noout -subject 2>/dev/null | cut -d= -f2- | tr -d ' ')
        
        if echo "${CERT_ISSUER}" | grep -qi "Let's Encrypt"; then
            IS_LETSENCRYPT=true
            echo "Certificate is a valid Let's Encrypt certificate"
        else
            # Check if issuer and subject are the same (self-signed)
            ISSUER_CN=$(openssl x509 -in "${CERT_FILE}" -noout -issuer 2>/dev/null | sed -n 's/.*CN=\([^,]*\).*/\1/p' | tr -d ' ')
            SUBJECT_CN=$(openssl x509 -in "${CERT_FILE}" -noout -subject 2>/dev/null | sed -n 's/.*CN=\([^,]*\).*/\1/p' | tr -d ' ')
            
            if [ -n "$ISSUER_CN" ] && [ -n "$SUBJECT_CN" ] && [ "$ISSUER_CN" = "$SUBJECT_CN" ]; then
                IS_SELF_SIGNED=true
                echo "Warning: Self-signed certificate detected (Issuer: ${ISSUER_CN}). This will be replaced with a Let's Encrypt certificate."
            fi
        fi
        
        # If self-signed, replace with Let's Encrypt certificate
        if [ "$IS_SELF_SIGNED" = true ]; then
            echo "Replacing self-signed certificate with Let's Encrypt certificate..."
            # Remove old self-signed certificates
            rm -f "${CERT_FILE}" "${KEY_FILE}"
            echo "Old self-signed certificates removed"
            # Fall through to obtain new certificates
        elif [ "$IS_LETSENCRYPT" = true ]; then
            # Check expiration for Let's Encrypt certificates
            EXPIRY_DATE=$(openssl x509 -enddate -noout -in "${CERT_FILE}" 2>/dev/null | cut -d= -f2)
            if [ -n "$EXPIRY_DATE" ]; then
                if [[ "$OSTYPE" == "linux-gnu"* ]]; then
                    EXPIRY_EPOCH=$(date -d "$EXPIRY_DATE" +%s 2>/dev/null)
                elif [[ "$OSTYPE" == "darwin"* ]]; then
                    EXPIRY_EPOCH=$(date -j -f "%b %d %H:%M:%S %Y %Z" "$EXPIRY_DATE" +%s 2>/dev/null)
                else
                    EXPIRY_EPOCH=$(date -d "$EXPIRY_DATE" +%s 2>/dev/null || echo "")
                fi
                CURRENT_EPOCH=$(date +%s)
                if [ -n "$EXPIRY_EPOCH" ] && [ -n "$CURRENT_EPOCH" ]; then
                    DAYS_UNTIL_EXPIRY=$(( ($EXPIRY_EPOCH - $CURRENT_EPOCH) / 86400 ))
                    if [ $DAYS_UNTIL_EXPIRY -lt 30 ]; then
                        echo "Certificate expires in $DAYS_UNTIL_EXPIRY days. Renewing..."
                        docker exec "${NGINX_CONTAINER}" certbot renew --quiet --no-self-upgrade --no-random-sleep-on-renew
                        
                        # Copy renewed certificates
                        if docker exec "${NGINX_CONTAINER}" test -f "${LETSENCRYPT_PATH}/fullchain.pem"; then
                            docker cp "${NGINX_CONTAINER}:${LETSENCRYPT_PATH}/fullchain.pem" "${CERT_FILE}"
                            docker cp "${NGINX_CONTAINER}:${LETSENCRYPT_PATH}/privkey.pem" "${KEY_FILE}"
                            chmod 644 "${CERT_FILE}" 2>/dev/null || true
                            chmod 600 "${KEY_FILE}" 2>/dev/null || true
                            echo "Certificates copied successfully"
                        fi
                        
                        # Reload nginx
                        docker exec "${NGINX_CONTAINER}" nginx -s reload
                        echo "SSL certificate renewed and nginx reloaded successfully"
                    else
                        echo "Certificate is valid for $DAYS_UNTIL_EXPIRY more days"
                        echo "Automatic renewal is configured via cron job inside nginx container (runs daily at 3 AM)"
                    fi
                else
                    echo "Could not calculate certificate expiry. Skipping renewal check."
                fi
            fi
            # Exit if Let's Encrypt certificate exists and is valid
            exit 0
        else
            echo "Warning: Could not determine certificate type. Attempting to obtain Let's Encrypt certificate..."
            rm -f "${CERT_FILE}" "${KEY_FILE}"
        fi
    else
        echo "Warning: openssl not found. Attempting to obtain Let's Encrypt certificate..."
        rm -f "${CERT_FILE}" "${KEY_FILE}"
    fi
fi

# If we reach here, we need to obtain new certificates (either none exist, or self-signed were removed)
if [ ! -f "${CERT_FILE}" ] || [ ! -f "${KEY_FILE}" ]; then
    echo "Obtaining new Let's Encrypt certificates..."
    
    # Check if nginx container is running
    if ! docker ps | grep -q "${NGINX_CONTAINER}"; then
        echo "Error: Nginx container is not running. Please start containers first: make up"
        exit 1
    fi
    
    # Check if nginx is running (process check)
    if ! docker exec "${NGINX_CONTAINER}" pgrep nginx &> /dev/null; then
        echo "Warning: Nginx process is not running. Starting nginx..."
        docker exec "${NGINX_CONTAINER}" nginx -g 'daemon off;' &
        sleep 5
    fi
    
    # Test nginx configuration before proceeding
    echo "Testing nginx configuration before certificate setup..."
    if ! docker exec "${NGINX_CONTAINER}" nginx -t 2>&1; then
        echo "Error: Nginx configuration is invalid. Please check nginx logs."
        echo "Showing nginx configuration test output:"
        docker exec "${NGINX_CONTAINER}" nginx -t 2>&1 || true
        echo ""
        echo "Showing recent nginx error logs:"
        docker logs "${NGINX_CONTAINER}" --tail 50 2>&1 | grep -i error || docker logs "${NGINX_CONTAINER}" --tail 20 2>&1
        exit 1
    fi
    echo "Nginx configuration is valid."
    
    # Test nginx configuration before obtaining certificates
    echo "Testing nginx configuration..."
    if ! docker exec "${NGINX_CONTAINER}" nginx -t 2>&1; then
        echo "Error: Nginx configuration is invalid. Please check nginx logs."
        echo "Attempting to show nginx error details..."
        docker exec "${NGINX_CONTAINER}" nginx -t 2>&1 || true
        docker logs "${NGINX_CONTAINER}" --tail 50 2>&1 || true
        exit 1
    fi
    
    # Ensure nginx is running
    if ! docker exec "${NGINX_CONTAINER}" pgrep nginx &> /dev/null; then
        echo "Starting nginx..."
        docker exec "${NGINX_CONTAINER}" nginx -g 'daemon off;' &
        sleep 5
    fi
    
    # Obtain certificates
    echo "Requesting SSL certificates for domains: ${DOMAIN}, www.${DOMAIN}"
    if docker exec "${NGINX_CONTAINER}" certbot certonly \
        --webroot \
        --webroot-path=/var/www/html/public \
        --email "${EMAIL}" \
        --agree-tos \
        --no-eff-email \
        --force-renewal \
        -d "${DOMAIN}" \
        -d "www.${DOMAIN}" \
        -d "coffeeglobe.com.sa" \
        -d "www.coffeeglobe.com.sa" 2>&1; then
        
        echo "SSL certificates obtained successfully"
        
        # Copy certificates to host
        if docker exec "${NGINX_CONTAINER}" test -f "${LETSENCRYPT_PATH}/fullchain.pem"; then
            docker cp "${NGINX_CONTAINER}:${LETSENCRYPT_PATH}/fullchain.pem" "${CERT_FILE}"
            docker cp "${NGINX_CONTAINER}:${LETSENCRYPT_PATH}/privkey.pem" "${KEY_FILE}"
            chmod 644 "${CERT_FILE}" 2>/dev/null || true
            chmod 600 "${KEY_FILE}" 2>/dev/null || true
            echo "Certificates copied to ${SSL_DIR}"
        else
            echo "Warning: Certificates obtained but not found at expected path"
        fi
        
        # Test nginx configuration
        if docker exec "${NGINX_CONTAINER}" nginx -t; then
            # Reload nginx to use new certificates
            docker exec "${NGINX_CONTAINER}" nginx -s reload
            echo "Nginx reloaded successfully with new SSL certificates"
            
            # Enable SSL stapling after successful reload
            echo "Enabling SSL stapling..."
            if [ -f "docker/ssl/enable-ssl-stapling.sh" ]; then
                chmod +x docker/ssl/enable-ssl-stapling.sh
                ./docker/ssl/enable-ssl-stapling.sh || echo "Warning: Could not enable SSL stapling automatically. Run 'make ssl-enable-stapling' manually."
            fi
        else
            echo "Error: Nginx configuration test failed after certificate installation"
            exit 1
        fi
        
        echo "SSL certificates obtained and configured successfully"
        echo "Automatic renewal is configured via cron job inside nginx container (runs daily at 3 AM)"
    else
        echo "Error: Failed to obtain SSL certificates. Please check:"
        echo "  1. Domain DNS settings point to this server"
        echo "  2. Port 80 is accessible from the internet"
        echo "  3. Nginx is running and serving /.well-known/acme-challenge/"
        echo "  4. Check certbot logs: docker logs ${NGINX_CONTAINER}"
        exit 1
    fi
fi
