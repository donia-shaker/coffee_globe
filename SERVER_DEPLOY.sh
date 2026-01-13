#!/bin/bash

set -e

cd /opt/coffee_globe

echo "=== Coffee Globe Deployment ==="

git pull

docker compose down

cat > .env << 'ENVEOF'
APP_NAME="Coffee Globe"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Riyadh
APP_URL=https://coffeeglobe.sa

APP_LOCALE=ar
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=ar_SA

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=coffee_globe
DB_USERNAME=coffee_globe_user
DB_PASSWORD=K8#mP9vL2@nQ5&wR7!xT4*yU6^zA1bN3#jK7
DB_ROOT_PASSWORD=Q3#bN8jM5@kP2&wX9!vL7*yR4^tS6hM9

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=
REDIS_DB=0
REDIS_CACHE_DB=1
REDIS_PREFIX=coffee_globe_

CACHE_STORE=file
CACHE_PREFIX=coffee_globe_cache_

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_EXPIRE_ON_CLOSE=false
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database-uuids

MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@coffeeglobe.sa"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_TO_ADDRESS=admin@coffeeglobe.sa
MAIL_TO_NAME="Admin"
MAIL_SENDMAIL_PATH="/usr/sbin/sendmail -bs -i"

FILESYSTEM_DISK=local

MEDIA_USE_STORAGE=false
MEDIA_USE_SERVER_STORAGE=true

LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=error
LOG_DAILY_DAYS=30

SANCTUM_STATEFUL_DOMAINS=coffeeglobe.sa,www.coffeeglobe.sa,coffeeglobe.com.sa,www.coffeeglobe.com.sa

SSL_DOMAIN=coffeeglobe.sa
SSL_EMAIL=admin@coffeeglobe.sa

VITE_APP_NAME="${APP_NAME}"
ENVEOF

mkdir -p docker/nginx/ssl

if [ ! -f docker/nginx/ssl/fullchain.pem ]; then
    openssl req -x509 -nodes -newkey rsa:2048 -days 1 \
        -keyout docker/nginx/ssl/privkey.pem \
        -out docker/nginx/ssl/fullchain.pem \
        -subj "/CN=coffeeglobe.sa" 2>/dev/null
    chmod 644 docker/nginx/ssl/fullchain.pem
    chmod 600 docker/nginx/ssl/privkey.pem
fi

docker compose build --no-cache

docker compose up -d

sleep 25

docker exec coffee_globe_php composer install --no-dev --optimize-autoloader

docker exec coffee_globe_php php artisan key:generate --force

docker exec coffee_globe_php chmod -R 775 storage bootstrap/cache server_storage 2>/dev/null || true

docker exec coffee_globe_php chown -R www-data:www-data storage bootstrap/cache server_storage 2>/dev/null || true

docker exec coffee_globe_php find storage -type d -exec chmod 775 {} \; 2>/dev/null || true
docker exec coffee_globe_php find storage -type f -exec chmod 664 {} \; 2>/dev/null || true

docker exec coffee_globe_php php artisan migrate:fresh --force

docker exec coffee_globe_php php artisan db:seed --force

docker exec coffee_globe_php php artisan storage:link

docker exec coffee_globe_php php artisan optimize

echo ""
echo "=== Deployment Complete ==="
echo ""
docker compose ps
echo ""
curl -I https://coffeeglobe.sa
