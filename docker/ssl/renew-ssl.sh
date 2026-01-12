#!/bin/bash

set -e

DOMAIN="${SSL_DOMAIN:-coffeeglobe.sa}"
NGINX_CONTAINER="coffee_globe_nginx"
SSL_DIR="/etc/letsencrypt/live/${DOMAIN}"

if ! docker ps | grep -q "${NGINX_CONTAINER}"; then
    echo "Nginx container is not running"
    exit 1
fi

echo "Checking SSL certificate renewal..."

docker exec ${NGINX_CONTAINER} certbot renew --quiet --no-self-upgrade --dry-run > /dev/null 2>&1

if [ $? -eq 0 ]; then
    RENEWAL_NEEDED=$(docker exec ${NGINX_CONTAINER} certbot renew --dry-run 2>&1 | grep -c "would be renewed" || true)
    
    if [ "${RENEWAL_NEEDED}" -gt 0 ]; then
        echo "Renewing SSL certificates..."
        
        docker exec ${NGINX_CONTAINER} certbot renew --quiet --no-self-upgrade
        
        if [ $? -eq 0 ]; then
            docker cp ${NGINX_CONTAINER}:${SSL_DIR}/fullchain.pem docker/nginx/ssl/fullchain.pem
            docker cp ${NGINX_CONTAINER}:${SSL_DIR}/privkey.pem docker/nginx/ssl/privkey.pem
            
            if [ -f "docker/nginx/ssl/fullchain.pem" ] && [ -f "docker/nginx/ssl/privkey.pem" ]; then
                docker exec ${NGINX_CONTAINER} nginx -s reload
                echo "SSL certificates renewed and nginx reloaded successfully"
            else
                echo "Failed to copy renewed certificates"
                exit 1
            fi
        else
            echo "Failed to renew certificates"
            exit 1
        fi
    else
        echo "No renewal needed"
    fi
else
    docker exec ${NGINX_CONTAINER} certbot renew --quiet --no-self-upgrade
    
    if [ $? -eq 0 ]; then
        docker cp ${NGINX_CONTAINER}:${SSL_DIR}/fullchain.pem docker/nginx/ssl/fullchain.pem 2>/dev/null || true
        docker cp ${NGINX_CONTAINER}:${SSL_DIR}/privkey.pem docker/nginx/ssl/privkey.pem 2>/dev/null || true
        docker exec ${NGINX_CONTAINER} nginx -s reload 2>/dev/null || true
    fi
fi
