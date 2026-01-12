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
DB_USER="${DB_USERNAME:-coffee_globe_user}"
DB_PASS="${DB_PASSWORD:-password}"
DB_ROOT_PASS="${DB_ROOT_PASSWORD:-root_password}"

until mysqladmin ping -h mysql -u "$DB_USER" -p"$DB_PASS" --silent 2>/dev/null || mysqladmin ping -h mysql -u root -p"$DB_ROOT_PASS" --silent 2>/dev/null; do
    echo "MySQL is unavailable - sleeping"
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
