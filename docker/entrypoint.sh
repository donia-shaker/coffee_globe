#!/bin/bash
set -e

if [ "$(id -u)" = "0" ]; then
    mkdir -p \
        /var/www/html/storage/app/public \
        /var/www/html/storage/framework/cache/data \
        /var/www/html/storage/framework/sessions \
        /var/www/html/storage/framework/views \
        /var/www/html/storage/logs \
        /var/www/html/bootstrap/cache \
        /var/www/html/server_storage/media \
        /var/log/php \
        /var/cache/nginx 2>/dev/null || true
    
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
    
    find /var/www/html/storage -type d -exec chmod 775 {} \; 2>/dev/null || true
    find /var/www/html/storage -type f -exec chmod 664 {} \; 2>/dev/null || true
    
    gosu www-data bash -c "
        php artisan migrate --force 2>&1 || echo 'Migrations completed with warnings'
        php artisan db:seed --force 2>&1 || echo 'Seeders completed with warnings'
        php artisan storage:link 2>&1 || true
        php artisan config:cache 2>&1 || true
        php artisan route:cache 2>&1 || true
        php artisan view:cache 2>&1 || true
    "
    
    exec php-fpm
fi

# This should never be reached if running as root
exec "$@"
