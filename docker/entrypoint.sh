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

# Run migrations (database should be ready due to depends_on in docker-compose.yml)
php artisan migrate --force || echo "Migrations completed with warnings"

# Run seeders
php artisan db:seed --force || echo "Seeders completed with warnings"

php artisan storage:link || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec php-fpm
