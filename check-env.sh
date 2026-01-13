#!/bin/bash

PHP_CONTAINER="coffee_globe_php"

echo "=== Checking .env Configuration ==="
echo ""

if [ -f .env ]; then
    echo "Host .env file:"
    grep -E "^DB_|^APP_" .env || echo "No DB/APP variables found"
    echo ""
else
    echo "✗ No .env file found on host"
    echo ""
fi

if docker ps | grep -q "${PHP_CONTAINER}"; then
    echo "Container .env file:"
    docker exec ${PHP_CONTAINER} cat .env | grep -E "^DB_|^APP_" || echo "No DB/APP variables found"
    echo ""
    
    echo "Laravel configuration:"
    docker exec ${PHP_CONTAINER} php artisan config:show database 2>/dev/null || echo "Cannot show config"
else
    echo "✗ PHP container is not running"
fi
