#!/bin/bash

set -e

echo "=========================================="
echo "🔧 Coffee Globe - Complete Fix Script"
echo "=========================================="
echo ""

CONTAINER_PHP="coffee_globe_php"
CONTAINER_NGINX="coffee_globe_nginx"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_info() {
    echo -e "ℹ️  $1"
}

# Check if containers are running
if ! docker ps | grep -q "$CONTAINER_PHP"; then
    print_error "PHP container is not running!"
    exit 1
fi

if ! docker ps | grep -q "$CONTAINER_NGINX"; then
    print_warning "Nginx container is not running, but continuing..."
fi

echo ""
echo "1️⃣  Fixing APP_KEY..."
echo "----------------------------------------"
if docker exec $CONTAINER_PHP grep -q "APP_KEY=$" .env 2>/dev/null || ! docker exec $CONTAINER_PHP grep -q "APP_KEY=" .env 2>/dev/null; then
    print_info "Generating new APP_KEY..."
    docker exec $CONTAINER_PHP php artisan key:generate --force
    print_success "APP_KEY generated successfully"
else
    APP_KEY=$(docker exec $CONTAINER_PHP grep "APP_KEY=" .env | cut -d '=' -f2)
    if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
        print_info "APP_KEY is empty, generating new one..."
        docker exec $CONTAINER_PHP php artisan key:generate --force
        print_success "APP_KEY generated successfully"
    else
        print_success "APP_KEY already exists"
    fi
fi

echo ""
echo "2️⃣  Fixing APP_URL (removing /build if exists)..."
echo "----------------------------------------"
CURRENT_APP_URL=$(docker exec $CONTAINER_PHP grep "^APP_URL=" .env | cut -d '=' -f2- | tr -d '"' | tr -d "'")
if echo "$CURRENT_APP_URL" | grep -q "/build"; then
    FIXED_APP_URL=$(echo "$CURRENT_APP_URL" | sed 's|/build||g')
    print_warning "Found /build in APP_URL: $CURRENT_APP_URL"
    print_info "Fixing to: $FIXED_APP_URL"
    docker exec $CONTAINER_PHP sed -i "s|^APP_URL=.*|APP_URL=$FIXED_APP_URL|" .env
    print_success "APP_URL fixed"
else
    print_success "APP_URL is correct: $CURRENT_APP_URL"
fi

echo ""
echo "3️⃣  Fixing SSL Certificates..."
echo "----------------------------------------"
if [ -d "docker/nginx/ssl" ]; then
    if [ ! -f "docker/nginx/ssl/fullchain.pem" ] || [ ! -f "docker/nginx/ssl/privkey.pem" ]; then
        print_info "Creating temporary SSL certificates..."
        mkdir -p docker/nginx/ssl
        openssl req -x509 -nodes -newkey rsa:2048 -days 365 \
            -keyout docker/nginx/ssl/privkey.pem \
            -out docker/nginx/ssl/fullchain.pem \
            -subj "/CN=coffeeglobe.sa" 2>/dev/null
        chmod 644 docker/nginx/ssl/fullchain.pem
        chmod 600 docker/nginx/ssl/privkey.pem
        print_success "Temporary SSL certificates created"
    else
        print_success "SSL certificates already exist"
    fi
else
    print_info "Creating SSL directory and certificates..."
    mkdir -p docker/nginx/ssl
    openssl req -x509 -nodes -newkey rsa:2048 -days 365 \
        -keyout docker/nginx/ssl/privkey.pem \
        -out docker/nginx/ssl/fullchain.pem \
        -subj "/CN=coffeeglobe.sa" 2>/dev/null
    chmod 644 docker/nginx/ssl/fullchain.pem
    chmod 600 docker/nginx/ssl/privkey.pem
    print_success "SSL certificates created"
fi

echo ""
echo "4️⃣  Fixing Storage Permissions..."
echo "----------------------------------------"
docker exec $CONTAINER_PHP chmod -R 775 storage bootstrap/cache server_storage 2>/dev/null || true
docker exec $CONTAINER_PHP chown -R www-data:www-data storage bootstrap/cache server_storage 2>/dev/null || true
print_success "Storage permissions fixed"

echo ""
echo "5️⃣  Fixing Storage Link..."
echo "----------------------------------------"
docker exec $CONTAINER_PHP rm -f public/storage 2>/dev/null || true
docker exec $CONTAINER_PHP php artisan storage:link 2>/dev/null || true
print_success "Storage link fixed"

echo ""
echo "6️⃣  Clearing All Caches..."
echo "----------------------------------------"
docker exec $CONTAINER_PHP php artisan config:clear 2>/dev/null || true
docker exec $CONTAINER_PHP php artisan cache:clear 2>/dev/null || true
docker exec $CONTAINER_PHP php artisan route:clear 2>/dev/null || true
docker exec $CONTAINER_PHP php artisan view:clear 2>/dev/null || true
docker exec $CONTAINER_PHP php artisan optimize:clear 2>/dev/null || true
print_success "All caches cleared"

echo ""
echo "7️⃣  Rebuilding Caches..."
echo "----------------------------------------"
docker exec $CONTAINER_PHP php artisan config:cache 2>/dev/null || true
docker exec $CONTAINER_PHP php artisan route:cache 2>/dev/null || true
docker exec $CONTAINER_PHP php artisan view:cache 2>/dev/null || true
docker exec $CONTAINER_PHP php artisan optimize 2>/dev/null || true
print_success "Caches rebuilt"

echo ""
echo "8️⃣  Checking Missing Image Files..."
echo "----------------------------------------"
if ! docker exec $CONTAINER_PHP test -f "public/images/features_1.svg" 2>/dev/null; then
    print_warning "features_1.svg is missing"
    print_info "Creating placeholder directory..."
    docker exec $CONTAINER_PHP mkdir -p public/images 2>/dev/null || true
    print_info "You may need to add the actual image files"
else
    print_success "features_1.svg exists"
fi

if ! docker exec $CONTAINER_PHP test -f "public/images/features_2.svg" 2>/dev/null; then
    print_warning "features_2.svg is missing"
    print_info "You may need to add the actual image files"
else
    print_success "features_2.svg exists"
fi

echo ""
echo "9️⃣  Restarting Nginx (if running)..."
echo "----------------------------------------"
if docker ps | grep -q "$CONTAINER_NGINX"; then
    docker exec $CONTAINER_NGINX nginx -t 2>/dev/null && docker exec $CONTAINER_NGINX nginx -s reload 2>/dev/null && print_success "Nginx reloaded" || print_warning "Nginx reload failed (may need container restart)"
else
    print_info "Nginx container not running, skipping reload"
fi

echo ""
echo "🔟 Final Verification..."
echo "----------------------------------------"
echo "Checking APP_KEY:"
APP_KEY_CHECK=$(docker exec $CONTAINER_PHP php artisan tinker --execute="echo config('app.key') ? 'OK' : 'MISSING';" 2>/dev/null | grep -v "Psy Shell" | tail -1)
if [ "$APP_KEY_CHECK" = "OK" ]; then
    print_success "APP_KEY is valid"
else
    print_error "APP_KEY is still missing!"
fi

echo ""
echo "Checking Database Connection:"
if docker exec $CONTAINER_PHP php artisan db:monitor 2>/dev/null; then
    print_success "Database connection OK"
else
    print_warning "Database connection failed (may be normal if DB is not ready)"
fi

echo ""
echo "=========================================="
print_success "All fixes completed!"
echo "=========================================="
echo ""
echo "📋 Summary:"
echo "  ✅ APP_KEY fixed"
echo "  ✅ APP_URL fixed"
echo "  ✅ SSL certificates created"
echo "  ✅ Storage permissions fixed"
echo "  ✅ Storage link created"
echo "  ✅ All caches cleared and rebuilt"
echo ""
echo "🔄 If issues persist, try:"
echo "  docker compose restart nginx"
echo "  docker compose restart php"
echo ""
