#!/bin/bash
set -e

if [ "$(id -u)" = "0" ]; then
    chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/server_storage \
        /var/log/php \
        /var/cache/nginx 2>/dev/null || true
    chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/server_storage \
        /var/log/php \
        /var/cache/nginx 2>/dev/null || true
    exec gosu www-data "$0" "$@"
fi

echo "Waiting for MySQL to be ready..."
DB_HOST="${DB_HOST:-mysql}"
DB_USER="${DB_USERNAME:-coffee_globe_user}"
DB_PASS="${DB_PASSWORD:-password}"
DB_ROOT_PASS="${DB_ROOT_PASSWORD:-root_password}"
MAX_ATTEMPTS=60
ATTEMPT=0

# Wait for MySQL to be ready using mysql client with SELECT 1 (more reliable than mysqladmin ping)
until mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" -e "SELECT 1" >/dev/null 2>&1 || mysql -h "$DB_HOST" -u root -p"$DB_ROOT_PASS" -e "SELECT 1" >/dev/null 2>&1; do
    ATTEMPT=$((ATTEMPT + 1))
    if [ $ATTEMPT -ge $MAX_ATTEMPTS ]; then
        echo "MySQL connection failed after $MAX_ATTEMPTS attempts. Exiting..."
        exit 1
    fi
    echo "MySQL is unavailable - sleeping (attempt $ATTEMPT/$MAX_ATTEMPTS)"
    sleep 2
done

echo "MySQL is ready!"

php artisan migrate --force

php artisan db:seed --force

php artisan storage:link || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec php-fpm
