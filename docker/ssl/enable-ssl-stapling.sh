#!/bin/bash

# Script to enable SSL stapling in nginx configuration after certificates are obtained

set -e

NGINX_CONF="docker/nginx/conf.d/coffeeglobe.sa.conf"

if [ ! -f "$NGINX_CONF" ]; then
    echo "Error: Nginx configuration file not found: $NGINX_CONF"
    exit 1
fi

# Check if certificates exist
SSL_DIR="docker/nginx/ssl"
CERT_FILE="${SSL_DIR}/fullchain.pem"
KEY_FILE="${SSL_DIR}/privkey.pem"

if [ ! -f "$CERT_FILE" ] || [ ! -f "$KEY_FILE" ]; then
    echo "Error: SSL certificates not found. Please run 'make ssl-setup' first."
    exit 1
fi

# Enable SSL stapling by uncommenting the lines
if grep -q "^[[:space:]]*# ssl_stapling on;" "$NGINX_CONF"; then
    echo "Enabling SSL stapling in nginx configuration..."
    
    # Use sed to uncomment SSL stapling lines
    if [[ "$OSTYPE" == "darwin"* ]]; then
        # macOS
        sed -i '' 's/^[[:space:]]*# ssl_stapling on;/    ssl_stapling on;/g' "$NGINX_CONF"
        sed -i '' 's/^[[:space:]]*# ssl_stapling_verify on;/    ssl_stapling_verify on;/g' "$NGINX_CONF"
    else
        # Linux
        sed -i 's/^[[:space:]]*# ssl_stapling on;/    ssl_stapling on;/g' "$NGINX_CONF"
        sed -i 's/^[[:space:]]*# ssl_stapling_verify on;/    ssl_stapling_verify on;/g' "$NGINX_CONF"
    fi
    
    echo "SSL stapling enabled in configuration file"
    
    # Test nginx configuration
    NGINX_CONTAINER="coffee_globe_nginx"
    if docker ps | grep -q "$NGINX_CONTAINER"; then
        echo "Testing nginx configuration..."
        if docker exec "$NGINX_CONTAINER" nginx -t 2>&1; then
            echo "Nginx configuration is valid. Reloading nginx..."
            docker exec "$NGINX_CONTAINER" nginx -s reload
            echo "✓ SSL stapling enabled and nginx reloaded successfully"
        else
            echo "Error: Nginx configuration test failed. Reverting changes..."
            # Revert changes
            if [[ "$OSTYPE" == "darwin"* ]]; then
                sed -i '' 's/^[[:space:]]*ssl_stapling on;/    # ssl_stapling on;/g' "$NGINX_CONF"
                sed -i '' 's/^[[:space:]]*ssl_stapling_verify on;/    # ssl_stapling_verify on;/g' "$NGINX_CONF"
            else
                sed -i 's/^[[:space:]]*ssl_stapling on;/    # ssl_stapling on;/g' "$NGINX_CONF"
                sed -i 's/^[[:space:]]*ssl_stapling_verify on;/    # ssl_stapling_verify on;/g' "$NGINX_CONF"
            fi
            exit 1
        fi
    else
        echo "Warning: Nginx container is not running. Configuration updated but nginx not reloaded."
        echo "Start nginx container and run: docker exec $NGINX_CONTAINER nginx -s reload"
    fi
else
    echo "SSL stapling is already enabled or configuration format is different"
    echo "Current SSL stapling configuration:"
    grep -A 1 "ssl_stapling" "$NGINX_CONF" || echo "No ssl_stapling found in configuration"
fi
