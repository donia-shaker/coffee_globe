#!/bin/bash

set -e

PHP_CONTAINER="coffee_globe_php"
MYSQL_CONTAINER="coffee_globe_mysql"

echo "=== Testing Database Connection ==="
echo ""

echo "1. Checking MySQL container status..."
if ! docker ps | grep -q "${MYSQL_CONTAINER}"; then
    echo "✗ MySQL container is not running"
    echo "Starting MySQL container..."
    docker compose up -d mysql
    echo "Waiting for MySQL to be ready..."
    sleep 15
fi
echo "✓ MySQL container is running"
echo ""

echo "2. Testing MySQL connection from host..."
if docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD:-root_password}" -e "SELECT 1" >/dev/null 2>&1; then
    echo "✓ MySQL is responding"
else
    echo "✗ MySQL is not responding"
    exit 1
fi
echo ""

echo "3. Checking database existence..."
DB_EXISTS=$(docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD:-root_password}" -e "SHOW DATABASES LIKE '${DB_DATABASE:-coffee_globe}';" --skip-column-names)
if [ -n "$DB_EXISTS" ]; then
    echo "✓ Database '${DB_DATABASE:-coffee_globe}' exists"
else
    echo "✗ Database '${DB_DATABASE:-coffee_globe}' does not exist"
    echo "Creating database..."
    docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD:-root_password}" -e "CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE:-coffee_globe}\`;"
    echo "✓ Database created"
fi
echo ""

echo "4. Checking database user..."
USER_EXISTS=$(docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD:-root_password}" -e "SELECT User FROM mysql.user WHERE User='${DB_USERNAME:-coffee_globe_user}';" --skip-column-names)
if [ -n "$USER_EXISTS" ]; then
    echo "✓ User '${DB_USERNAME:-coffee_globe_user}' exists"
else
    echo "✗ User '${DB_USERNAME:-coffee_globe_user}' does not exist"
    echo "Creating user..."
    docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD:-root_password}" -e "CREATE USER IF NOT EXISTS '${DB_USERNAME:-coffee_globe_user}'@'%' IDENTIFIED BY '${DB_PASSWORD:-password}';"
    docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD:-root_password}" -e "GRANT ALL PRIVILEGES ON \`${DB_DATABASE:-coffee_globe}\`.* TO '${DB_USERNAME:-coffee_globe_user}'@'%';"
    docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD:-root_password}" -e "FLUSH PRIVILEGES;"
    echo "✓ User created and granted privileges"
fi
echo ""

echo "5. Testing connection from PHP container..."
if docker ps | grep -q "${PHP_CONTAINER}"; then
    if docker exec ${PHP_CONTAINER} php -r "new PDO('mysql:host=mysql;port=3306;dbname=${DB_DATABASE:-coffee_globe}', '${DB_USERNAME:-coffee_globe_user}', '${DB_PASSWORD:-password}');" 2>/dev/null; then
        echo "✓ PHP can connect to MySQL"
    else
        echo "✗ PHP cannot connect to MySQL"
        echo ""
        echo "Checking Laravel .env configuration..."
        docker exec ${PHP_CONTAINER} cat .env | grep -E "^DB_"
        exit 1
    fi
else
    echo "✗ PHP container is not running"
    echo "Starting PHP container..."
    docker compose up -d php
    sleep 10
    docker exec ${PHP_CONTAINER} php -r "new PDO('mysql:host=mysql;port=3306;dbname=${DB_DATABASE:-coffee_globe}', '${DB_USERNAME:-coffee_globe_user}', '${DB_PASSWORD:-password}');" 2>/dev/null && echo "✓ PHP can connect to MySQL" || echo "✗ PHP cannot connect to MySQL"
fi
echo ""

echo "6. Testing Laravel database connection..."
if docker exec ${PHP_CONTAINER} php artisan db:show 2>/dev/null; then
    echo "✓ Laravel can connect to database"
else
    echo "✗ Laravel cannot connect to database"
    echo "Showing error details..."
    docker exec ${PHP_CONTAINER} php artisan db:show 2>&1 || true
    exit 1
fi
echo ""

echo "7. Checking migrations table..."
if docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD:-root_password}" -D "${DB_DATABASE:-coffee_globe}" -e "SHOW TABLES LIKE 'migrations';" --skip-column-names | grep -q migrations; then
    echo "✓ Migrations table exists"
    MIGRATION_COUNT=$(docker exec ${MYSQL_CONTAINER} mysql -h 127.0.0.1 -u root -p"${DB_ROOT_PASSWORD:-root_password}" -D "${DB_DATABASE:-coffee_globe}" -e "SELECT COUNT(*) FROM migrations;" --skip-column-names)
    echo "  Migrations run: ${MIGRATION_COUNT}"
else
    echo "✗ Migrations table does not exist"
    echo "Run: make artisan-migrate"
fi
echo ""

echo "=== Database Connection Test Complete ==="
echo ""
echo "Current configuration:"
echo "  DB_HOST: mysql"
echo "  DB_PORT: 3306"
echo "  DB_DATABASE: ${DB_DATABASE:-coffee_globe}"
echo "  DB_USERNAME: ${DB_USERNAME:-coffee_globe_user}"
