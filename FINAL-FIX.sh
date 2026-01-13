#!/bin/bash

set -e

echo "=========================================="
echo "Coffee Globe - Complete Fix Script"
echo "=========================================="
echo ""

cd /opt/coffee_globe

echo "Step 1: Pulling latest code..."
git pull
echo ""

echo "Step 2: Stopping containers..."
docker compose down
echo ""

echo "Step 3: Creating SSL directory and temp certificates..."
mkdir -p docker/nginx/ssl
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout docker/nginx/ssl/privkey.pem \
    -out docker/nginx/ssl/fullchain.pem \
    -subj "/C=SA/ST=Riyadh/L=Riyadh/O=Coffee Globe/CN=coffeeglobe.sa" 2>/dev/null
chmod 644 docker/nginx/ssl/fullchain.pem
chmod 600 docker/nginx/ssl/privkey.pem
echo "✓ Temp certificates created"
echo ""

echo "Step 4: Creating .well-known directory..."
mkdir -p public/.well-known/acme-challenge
chmod 755 public/.well-known
chmod 755 public/.well-known/acme-challenge
echo "✓ Directory created"
echo ""

echo "Step 5: Starting all containers..."
docker compose up -d
echo "Waiting for containers..."
sleep 20
echo ""

echo "Step 6: Fixing database..."
if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
fi

DB_ROOT_PASSWORD=$(docker exec coffee_globe_mysql printenv MYSQL_ROOT_PASSWORD 2>/dev/null || echo "root_password")
DB_DATABASE="${DB_DATABASE:-coffee_globe}"
DB_USERNAME="${DB_USERNAME:-coffee_globe_user}"
DB_PASSWORD="${DB_PASSWORD:-password}"

docker exec coffee_globe_mysql mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD}" << EOF
CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USERNAME}'@'%' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${DB_DATABASE}\`.* TO '${DB_USERNAME}'@'%';
FLUSH PRIVILEGES;
EOF
echo "✓ Database configured"
echo ""

echo "Step 7: Running migrations..."
docker exec coffee_globe_php php artisan migrate --force
echo "✓ Migrations completed"
echo ""

echo "Step 8: Obtaining Let's Encrypt certificates..."
docker exec coffee_globe_nginx certbot certonly \
    --webroot \
    --webroot-path=/var/www/html/public \
    --email admin@coffeeglobe.sa \
    --agree-tos \
    --no-eff-email \
    --force-renewal \
    --non-interactive \
    -d coffeeglobe.sa \
    -d www.coffeeglobe.sa \
    -d coffeeglobe.com.sa \
    -d www.coffeeglobe.com.sa

if [ $? -eq 0 ]; then
    docker cp coffee_globe_nginx:/etc/letsencrypt/live/coffeeglobe.sa/fullchain.pem docker/nginx/ssl/
    docker cp coffee_globe_nginx:/etc/letsencrypt/live/coffeeglobe.sa/privkey.pem docker/nginx/ssl/
    chmod 644 docker/nginx/ssl/fullchain.pem
    chmod 600 docker/nginx/ssl/privkey.pem
    docker exec coffee_globe_nginx nginx -s reload
    echo "✓ SSL certificates obtained and configured"
else
    echo "⚠ SSL certificate request failed - site running with self-signed certificates"
fi
echo ""

echo "Step 9: Clearing caches..."
docker exec coffee_globe_php php artisan config:cache
docker exec coffee_globe_php php artisan route:cache
docker exec coffee_globe_php php artisan view:cache
echo "✓ Caches cleared"
echo ""

echo "=========================================="
echo "✓ Setup Complete!"
echo "=========================================="
echo ""
echo "Container status:"
docker compose ps
echo ""
echo "Test your site:"
echo "  http://coffeeglobe.sa"
echo "  https://coffeeglobe.sa"
