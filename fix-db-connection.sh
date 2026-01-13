#!/bin/bash

set -e

MYSQL_CONTAINER="coffee_globe_mysql"
PHP_CONTAINER="coffee_globe_php"
DB_DATABASE="${DB_DATABASE:-coffee_globe}"
DB_USERNAME="${DB_USERNAME:-coffee_globe_user}"
DB_PASSWORD="${DB_PASSWORD:-password}"
DB_ROOT_PASSWORD="${DB_ROOT_PASSWORD:-root_password}"

echo "=== Fixing Database Connection ==="
echo ""

echo "Step 1: Ensuring MySQL is running..."
docker compose up -d mysql
sleep 10

for i in {1..30}; do
    if docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD}" -e "SELECT 1" >/dev/null 2>&1; then
        echo "✓ MySQL is ready"
        break
    fi
    if [ $i -eq 30 ]; then
        echo "✗ MySQL failed to start"
        docker logs ${MYSQL_CONTAINER} --tail 50
        exit 1
    fi
    sleep 1
done
echo ""

echo "Step 2: Creating database if not exists..."
docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD}" -e "CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo "✓ Database created/verified"
echo ""

echo "Step 3: Creating/updating database user..."
docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD}" << EOF
DROP USER IF EXISTS '${DB_USERNAME}'@'%';
CREATE USER '${DB_USERNAME}'@'%' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${DB_DATABASE}\`.* TO '${DB_USERNAME}'@'%';
FLUSH PRIVILEGES;
EOF
echo "✓ User created and privileges granted"
echo ""

echo "Step 4: Verifying connection from MySQL..."
if docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u "${DB_USERNAME}" -p"${DB_PASSWORD}" -D "${DB_DATABASE}" -e "SELECT 1" >/dev/null 2>&1; then
    echo "✓ User can connect to database"
else
    echo "✗ User cannot connect"
    exit 1
fi
echo ""

echo "Step 5: Ensuring PHP container is running..."
docker compose up -d php
sleep 10

for i in {1..30}; do
    if docker exec ${PHP_CONTAINER} php-fpm-healthcheck 2>/dev/null; then
        echo "✓ PHP-FPM is ready"
        break
    fi
    sleep 1
done
echo ""

echo "Step 6: Testing Laravel database connection..."
if docker exec ${PHP_CONTAINER} php artisan db:show >/dev/null 2>&1; then
    echo "✓ Laravel can connect to database"
else
    echo "✗ Laravel cannot connect"
    echo ""
    echo "Checking .env configuration..."
    docker exec ${PHP_CONTAINER} cat .env | grep -E "^DB_" || echo "No DB_ variables found in .env"
    echo ""
    echo "Expected values:"
    echo "  DB_CONNECTION=mysql"
    echo "  DB_HOST=mysql"
    echo "  DB_PORT=3306"
    echo "  DB_DATABASE=${DB_DATABASE}"
    echo "  DB_USERNAME=${DB_USERNAME}"
    echo "  DB_PASSWORD=${DB_PASSWORD}"
    exit 1
fi
echo ""

echo "Step 7: Running migrations..."
if docker exec ${PHP_CONTAINER} php artisan migrate --force; then
    echo "✓ Migrations completed"
else
    echo "✗ Migrations failed"
    docker exec ${PHP_CONTAINER} php artisan migrate --force 2>&1
    exit 1
fi
echo ""

echo "=== Database Connection Fixed ==="
echo ""
echo "You can now run:"
echo "  make artisan-seed    - Seed the database"
echo "  make artisan-cache   - Clear caches"
echo "  make up              - Start all services"
