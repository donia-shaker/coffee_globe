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

# Create storage link if it doesn't exist
php artisan storage:link || true

# Cache configuration (optional, can be run manually)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start PHP-FPM
exec php-fpm
