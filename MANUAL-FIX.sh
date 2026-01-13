#!/bin/bash

set -e

cd /opt/coffee_globe

echo "Step 1: Loading .env file..."
if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
fi

echo "Step 2: Waiting for MySQL..."
sleep 5

echo "Step 3: Getting MySQL root password from container..."
DB_ROOT_PASSWORD=$(docker exec coffee_globe_mysql printenv MYSQL_ROOT_PASSWORD)

echo "Step 4: Fixing database..."
docker exec coffee_globe_mysql mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD}" << 'EOF'
CREATE DATABASE IF NOT EXISTS `coffee_globe` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'coffee_globe_user'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON `coffee_globe`.* TO 'coffee_globe_user'@'%';
FLUSH PRIVILEGES;
EOF

echo "Step 5: Running migrations..."
docker exec coffee_globe_php php artisan migrate --force

echo "Step 6: Getting SSL certificates..."
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
    docker exec coffee_globe_nginx nginx -s reload
    echo "✓ SSL configured"
fi

echo "Step 7: Clearing caches..."
docker exec coffee_globe_php php artisan config:cache
docker exec coffee_globe_php php artisan route:cache
docker exec coffee_globe_php php artisan view:cache

echo "✓ Done"
docker compose ps
