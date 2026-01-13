#!/bin/bash

set -e

echo "=========================================="
echo "Coffee Globe - System Diagnostic Script"
echo "=========================================="
echo ""

COMPOSE="docker compose"
PHP_CONTAINER="coffee_globe_php"
NGINX_CONTAINER="coffee_globe_nginx"
MYSQL_CONTAINER="coffee_globe_mysql"
REDIS_CONTAINER="coffee_globe_redis"

# Check Docker
echo "=== Docker Status ==="
if command -v docker &> /dev/null; then
    echo "✓ Docker is installed"
    docker --version
else
    echo "✗ Docker is not installed"
    exit 1
fi

if docker compose version &> /dev/null; then
    echo "✓ Docker Compose is available"
    docker compose version
else
    echo "✗ Docker Compose is not available"
    exit 1
fi
echo ""

# Check Containers
echo "=== Container Status ==="
$COMPOSE ps
echo ""

# Check PHP Container
echo "=== PHP Container Status ==="
if docker ps | grep -q "$PHP_CONTAINER"; then
    echo "✓ PHP container is running"
    if docker exec "$PHP_CONTAINER" php-fpm-healthcheck 2>/dev/null; then
        echo "✓ PHP-FPM is healthy"
    else
        echo "✗ PHP-FPM health check failed"
    fi
    echo "PHP Version:"
    docker exec "$PHP_CONTAINER" php -v 2>/dev/null | head -1 || echo "Could not get PHP version"
else
    echo "✗ PHP container is not running"
fi
echo ""

# Check MySQL Container
echo "=== MySQL Container Status ==="
if docker ps | grep -q "$MYSQL_CONTAINER"; then
    echo "✓ MySQL container is running"
    if docker exec "$MYSQL_CONTAINER" mysqladmin ping -h localhost -u root -p${DB_ROOT_PASSWORD:-root_password} 2>/dev/null; then
        echo "✓ MySQL is responding"
    else
        echo "✗ MySQL is not responding"
    fi
    echo "MySQL Version:"
    docker exec "$MYSQL_CONTAINER" mysql --version 2>/dev/null || echo "Could not get MySQL version"
else
    echo "✗ MySQL container is not running"
fi
echo ""

# Check Redis Container
echo "=== Redis Container Status ==="
if docker ps | grep -q "$REDIS_CONTAINER"; then
    echo "✓ Redis container is running"
    if docker exec "$REDIS_CONTAINER" redis-cli ping 2>/dev/null | grep -q "PONG"; then
        echo "✓ Redis is responding"
    else
        echo "✗ Redis is not responding"
    fi
else
    echo "✗ Redis container is not running"
fi
echo ""

# Check Nginx Container
echo "=== Nginx Container Status ==="
if docker ps | grep -q "$NGINX_CONTAINER"; then
    echo "✓ Nginx container is running"
    if docker exec "$NGINX_CONTAINER" nginx -t 2>/dev/null; then
        echo "✓ Nginx configuration is valid"
    else
        echo "✗ Nginx configuration has errors:"
        docker exec "$NGINX_CONTAINER" nginx -t 2>&1 || true
    fi
    if docker exec "$NGINX_CONTAINER" pgrep nginx &> /dev/null; then
        echo "✓ Nginx process is running"
    else
        echo "✗ Nginx process is not running"
    fi
    echo "Nginx Version:"
    docker exec "$NGINX_CONTAINER" nginx -v 2>&1 || echo "Could not get Nginx version"
else
    echo "✗ Nginx container is not running"
fi
echo ""

# Check SSL Certificates
echo "=== SSL Certificate Status ==="
SSL_DIR="docker/nginx/ssl"
CERT_FILE="${SSL_DIR}/fullchain.pem"
KEY_FILE="${SSL_DIR}/privkey.pem"

if [ -f "$CERT_FILE" ] && [ -f "$KEY_FILE" ]; then
    echo "✓ SSL certificate files exist"
    if command -v openssl &> /dev/null; then
        echo "Certificate Details:"
        openssl x509 -in "$CERT_FILE" -noout -subject -issuer -dates 2>/dev/null || echo "Could not read certificate"
        
        # Check expiration
        EXPIRY_DATE=$(openssl x509 -enddate -noout -in "$CERT_FILE" 2>/dev/null | cut -d= -f2)
        if [ -n "$EXPIRY_DATE" ]; then
            if [[ "$OSTYPE" == "linux-gnu"* ]]; then
                EXPIRY_EPOCH=$(date -d "$EXPIRY_DATE" +%s 2>/dev/null)
            else
                EXPIRY_EPOCH=$(date -j -f "%b %d %H:%M:%S %Y %Z" "$EXPIRY_DATE" +%s 2>/dev/null || date -d "$EXPIRY_DATE" +%s 2>/dev/null)
            fi
            CURRENT_EPOCH=$(date +%s)
            if [ -n "$EXPIRY_EPOCH" ] && [ -n "$CURRENT_EPOCH" ]; then
                DAYS_UNTIL_EXPIRY=$(( ($EXPIRY_EPOCH - $CURRENT_EPOCH) / 86400 ))
                echo "Certificate expires in: $DAYS_UNTIL_EXPIRY days"
            fi
        fi
    fi
else
    echo "✗ SSL certificate files not found"
    echo "  Certificate file: $CERT_FILE"
    echo "  Key file: $KEY_FILE"
    echo "  Run 'make ssl-setup' to generate certificates"
fi
echo ""

# Check Laravel Application
echo "=== Laravel Application Status ==="
if docker ps | grep -q "$PHP_CONTAINER"; then
    echo "Checking Laravel configuration..."
    if docker exec "$PHP_CONTAINER" php artisan --version 2>/dev/null; then
        echo "✓ Laravel is accessible"
        
        echo "Migration Status:"
        docker exec "$PHP_CONTAINER" php artisan migrate:status 2>/dev/null | head -20 || echo "Could not check migration status"
        
        echo ""
        echo "Environment Check:"
        docker exec "$PHP_CONTAINER" php artisan env 2>/dev/null || echo "Could not check environment"
    else
        echo "✗ Laravel is not accessible"
    fi
fi
echo ""

# Check Network
echo "=== Network Status ==="
if docker network ls | grep -q "coffee_globe_network"; then
    echo "✓ Docker network exists"
else
    echo "✗ Docker network does not exist"
fi
echo ""

# Check Volumes
echo "=== Volume Status ==="
docker volume ls | grep coffee_globe || echo "No coffee_globe volumes found"
echo ""

# Check Recent Logs
echo "=== Recent Error Logs (last 10 lines) ==="
echo "PHP Errors:"
docker logs "$PHP_CONTAINER" --tail 10 2>&1 | grep -i error || echo "No PHP errors found"
echo ""
echo "Nginx Errors:"
docker logs "$NGINX_CONTAINER" --tail 10 2>&1 | grep -i error || echo "No Nginx errors found"
echo ""
echo "MySQL Errors:"
docker logs "$MYSQL_CONTAINER" --tail 10 2>&1 | grep -i error || echo "No MySQL errors found"
echo ""

echo "=========================================="
echo "Diagnostic Complete"
echo "=========================================="
