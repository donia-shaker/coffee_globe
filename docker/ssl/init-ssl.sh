#!/bin/bash

set -e

DOMAIN="${DOMAIN:-coffeeglobe.sa}"
EMAIL="${EMAIL:-admin@coffeeglobe.sa}"
NGINX_CONTAINER="coffee_globe_nginx"
SSL_DIR="docker/nginx/ssl"

if [ -d "$SSL_DIR" ] && [ "$(ls -A $SSL_DIR)" ]; then
    if openssl x509 -in "$SSL_DIR/fullchain.pem" -noout -issuer 2>/dev/null | grep -qi "Let's Encrypt"; then
        echo "Valid Let's Encrypt certificates exist. Skipping..."
        exit 0
    fi
fi

mkdir -p "$SSL_DIR"

openssl req -x509 -nodes -newkey rsa:2048 -days 1 \
    -keyout "$SSL_DIR/privkey.pem" \
    -out "$SSL_DIR/fullchain.pem" \
    -subj "/CN=$DOMAIN" 2>/dev/null

chmod 644 "$SSL_DIR/fullchain.pem"
chmod 600 "$SSL_DIR/privkey.pem"

docker compose up -d nginx

sleep 15

for i in {1..30}; do
    if docker exec "$NGINX_CONTAINER" nginx -t 2>/dev/null; then
        break
    fi
    sleep 1
done

if docker exec "$NGINX_CONTAINER" certbot certonly \
    --webroot \
    --webroot-path=/var/www/certbot \
    --email "$EMAIL" \
    --agree-tos \
    --no-eff-email \
    --force-renewal \
    --non-interactive \
    -d "$DOMAIN" \
    -d "www.$DOMAIN" \
    -d "coffeeglobe.com.sa" \
    -d "www.coffeeglobe.com.sa" 2>&1; then
    
    docker exec "$NGINX_CONTAINER" cat "/etc/letsencrypt/live/$DOMAIN/fullchain.pem" > "$SSL_DIR/fullchain.pem"
    docker exec "$NGINX_CONTAINER" cat "/etc/letsencrypt/live/$DOMAIN/privkey.pem" > "$SSL_DIR/privkey.pem"
    
    chmod 644 "$SSL_DIR/fullchain.pem"
    chmod 600 "$SSL_DIR/privkey.pem"
    
    docker exec "$NGINX_CONTAINER" nginx -s reload
    
    echo "SSL certificates obtained successfully"
else
    echo "Failed to obtain Let's Encrypt certificates - using self-signed"
    exit 1
fi
