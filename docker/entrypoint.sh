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
        /var/www/html/public/images \
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
    
    if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
        echo "Installing Composer dependencies..."
        gosu www-data composer install --no-dev --optimize-autoloader --no-interaction 2>&1 || echo "Composer install completed with warnings"
    else
        echo "Vendor directory exists, skipping composer install"
    fi
    
    # Check and generate APP_KEY if missing or empty
    if [ -f ".env" ]; then
        APP_KEY_VALUE=$(grep "^APP_KEY=" .env 2>/dev/null | cut -d '=' -f2- | tr -d ' ' || echo "")
        if [ -z "$APP_KEY_VALUE" ] || [ "$APP_KEY_VALUE" = "" ] || ! echo "$APP_KEY_VALUE" | grep -q "base64:"; then
            echo "APP_KEY is missing or invalid, generating new one..."
            gosu www-data php artisan key:generate --force 2>&1 || echo "APP_KEY generation completed with warnings"
        else
            echo "APP_KEY already exists and is valid"
        fi
    fi
    
    echo "Waiting for MySQL to be ready..."
    MAX_RETRIES=30
    RETRY_COUNT=0
    until php artisan db:show &> /dev/null || [ $RETRY_COUNT -eq $MAX_RETRIES ]
    do
        echo "MySQL is unavailable - sleeping (attempt $((RETRY_COUNT+1))/$MAX_RETRIES)"
        RETRY_COUNT=$((RETRY_COUNT+1))
        sleep 2
    done
    
    if [ $RETRY_COUNT -eq $MAX_RETRIES ]; then
        echo "WARNING: Could not connect to MySQL after $MAX_RETRIES attempts"
        echo "Skipping migrations and continuing with PHP-FPM startup"
    else
        echo "MySQL is ready - running database operations..."
        
        gosu www-data bash -c "
            php artisan migrate --force 2>&1 || echo 'Migrations completed with warnings'
            php artisan db:seed --force 2>&1 || echo 'Seeders completed with warnings'
            php artisan storage:link 2>&1 || true
            # Create missing image directories and placeholder files
            mkdir -p public/images 2>/dev/null || true
            touch public/images/features_1.svg public/images/features_2.svg 2>/dev/null || true
            php artisan config:cache 2>&1 || true
            php artisan route:cache 2>&1 || true
            php artisan view:cache 2>&1 || true
        "
    fi
    
    exec php-fpm
fi

# This should never be reached if running as root
exec "$@"
