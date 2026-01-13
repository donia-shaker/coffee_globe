#!/bin/bash
set -e

if [ "$(id -u)" = "0" ]; then
    # Ensure directories exist
    mkdir -p /var/log/php /var/cache/nginx 2>/dev/null || true
    
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

php artisan migrate --force

php artisan db:seed --force

php artisan storage:link || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec php-fpm
