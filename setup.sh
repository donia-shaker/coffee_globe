#!/bin/bash

set -e

cd /opt/coffee_globe

git pull

docker compose down

rm -rf docker/nginx/ssl/*

mkdir -p docker/nginx/ssl

openssl req -x509 -nodes -newkey rsa:2048 -days 1 \
    -keyout docker/nginx/ssl/privkey.pem \
    -out docker/nginx/ssl/fullchain.pem \
    -subj "/CN=coffeeglobe.sa" 2>/dev/null

chmod 644 docker/nginx/ssl/fullchain.pem
chmod 600 docker/nginx/ssl/privkey.pem

docker compose up -d

sleep 20

for i in {1..30}; do
    if docker exec coffee_globe_nginx nginx -t 2>/dev/null; then
        break
    fi
    sleep 1
done

if docker exec coffee_globe_nginx certbot certonly \
    --webroot \
    --webroot-path=/var/www/certbot \
    --email admin@coffeeglobe.sa \
    --agree-tos \
    --no-eff-email \
    --force-renewal \
    --non-interactive \
    -d coffeeglobe.sa \
    -d www.coffeeglobe.sa \
    -d coffeeglobe.com.sa \
    -d www.coffeeglobe.com.sa 2>&1; then
    
    docker exec coffee_globe_nginx cat /etc/letsencrypt/live/coffeeglobe.sa/fullchain.pem > docker/nginx/ssl/fullchain.pem
    docker exec coffee_globe_nginx cat /etc/letsencrypt/live/coffeeglobe.sa/privkey.pem > docker/nginx/ssl/privkey.pem
    
    chmod 644 docker/nginx/ssl/fullchain.pem
    chmod 600 docker/nginx/ssl/privkey.pem
    
    docker exec coffee_globe_nginx nginx -s reload
    
    echo "SSL certificates obtained successfully"
else
    echo "Using self-signed certificates - check DNS and firewall"
fi

docker exec coffee_globe_php php artisan migrate --force

docker compose ps
