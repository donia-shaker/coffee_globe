#!/bin/bash

set -e

DOMAIN="${SSL_DOMAIN:-coffeeglobe.sa}"
EMAIL="${SSL_EMAIL:-admin@coffeeglobe.sa}"
NGINX_CONTAINER="coffee_globe_nginx"

if [ ! -d "docker/nginx/ssl" ]; then
    mkdir -p docker/nginx/ssl
fi

if ! docker ps | grep -q "${NGINX_CONTAINER}"; then
    echo "Nginx container is not running. Please start containers first: make up"
    exit 1
fi

if [ -f "docker/nginx/ssl/fullchain.pem" ] && [ -f "docker/nginx/ssl/privkey.pem" ]; then
    echo "SSL certificates already exist. Checking expiration..."
    
    if command -v openssl &> /dev/null; then
        EXPIRY_DATE=$(openssl x509 -enddate -noout -in docker/nginx/ssl/fullchain.pem 2>/dev/null | cut -d= -f2)
        if [ -n "$EXPIRY_DATE" ]; then
            if [[ "$OSTYPE" == "linux-gnu"* ]]; then
                EXPIRY_EPOCH=$(date -d "$EXPIRY_DATE" +%s 2>/dev/null)
            elif [[ "$OSTYPE" == "darwin"* ]]; then
                EXPIRY_EPOCH=$(date -j -f "%b %d %H:%M:%S %Y %Z" "$EXPIRY_DATE" +%s 2>/dev/null)
            fi
            CURRENT_EPOCH=$(date +%s)
            if [ -n "$EXPIRY_EPOCH" ] && [ -n "$CURRENT_EPOCH" ]; then
                DAYS_UNTIL_EXPIRY=$(( ($EXPIRY_EPOCH - $CURRENT_EPOCH) / 86400 ))
                if [ $DAYS_UNTIL_EXPIRY -lt 30 ]; then
                    echo "Certificate expires in $DAYS_UNTIL_EXPIRY days. Renewing..."
                    docker exec $NGINX_CONTAINER certbot renew --quiet --no-self-upgrade
                    docker cp $NGINX_CONTAINER:/etc/letsencrypt/live/$DOMAIN/fullchain.pem docker/nginx/ssl/fullchain.pem 2>/dev/null || true
                    docker cp $NGINX_CONTAINER:/etc/letsencrypt/live/$DOMAIN/privkey.pem docker/nginx/ssl/privkey.pem 2>/dev/null || true
                    docker exec $NGINX_CONTAINER nginx -s reload 2>/dev/null || true
                    echo "SSL certificate renewed successfully"
                else
                    echo "Certificate is valid for $DAYS_UNTIL_EXPIRY more days"
                    echo "Automatic renewal is configured via cron job inside nginx container"
                fi
            fi
        fi
    fi
else
    echo "No SSL certificates found. Obtaining new certificates..."
    
    docker exec $NGINX_CONTAINER certbot certonly \
        --webroot \
        --webroot-path=/var/www/html/public \
        --email $EMAIL \
        --agree-tos \
        --no-eff-email \
        --force-renewal \
        -d $DOMAIN \
        -d www.$DOMAIN 2>&1
    
    if [ $? -eq 0 ]; then
        docker cp $NGINX_CONTAINER:/etc/letsencrypt/live/$DOMAIN/fullchain.pem docker/nginx/ssl/fullchain.pem 2>/dev/null || true
        docker cp $NGINX_CONTAINER:/etc/letsencrypt/live/$DOMAIN/privkey.pem docker/nginx/ssl/privkey.pem 2>/dev/null || true
        docker exec $NGINX_CONTAINER nginx -s reload 2>/dev/null || true
        echo "SSL certificates obtained and configured successfully"
        echo "Automatic renewal is configured via cron job inside nginx container (runs daily at 3 AM)"
    else
        echo "Failed to obtain SSL certificates. Please check domain DNS settings."
        exit 1
    fi
fi
