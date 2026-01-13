#!/bin/bash

echo "==================================="
echo "Coffee Globe - System Verification"
echo "==================================="
echo ""

cd /opt/coffee_globe

echo "1. Container Status"
docker compose ps
echo ""

echo "2. PHP-FPM Health"
docker exec coffee_globe_php php-fpm-healthcheck
echo ""

echo "3. Nginx Configuration"
docker exec coffee_globe_nginx nginx -t
echo ""

echo "4. SSL Certificate Status"
docker exec coffee_globe_nginx certbot certificates
echo ""

echo "5. SSL Certificate Files"
ls -lh docker/nginx/ssl/
echo ""

echo "6. Database Connection"
docker exec coffee_globe_php php artisan db:show
echo ""

echo "7. Migration Status"
docker exec coffee_globe_php php artisan migrate:status
echo ""

echo "8. Storage Permissions"
docker exec coffee_globe_php ls -la storage/ | head -10
echo ""

echo "9. Bootstrap Cache Permissions"
docker exec coffee_globe_php ls -la bootstrap/cache/ | head -10
echo ""

echo "10. Server Storage Permissions"
docker exec coffee_globe_php ls -la server_storage/ | head -10
echo ""

echo "11. Redis Connection"
docker exec coffee_globe_redis redis-cli ping
echo ""

echo "12. MySQL Connection"
docker exec coffee_globe_mysql mysql -u root -p"${DB_ROOT_PASSWORD:-Q3#bN8jM5@kP2&wX9!vL7*yR4^tS6hM9}" -e "SELECT 1" 2>/dev/null && echo "MySQL OK"
echo ""

echo "13. HTTP Response"
curl -I http://coffeeglobe.sa 2>&1 | head -10
echo ""

echo "14. HTTPS Response"
curl -I https://coffeeglobe.sa 2>&1 | head -10
echo ""

echo "15. SSL Certificate Expiry"
openssl x509 -in docker/nginx/ssl/fullchain.pem -noout -dates 2>/dev/null || echo "Cannot read certificate"
echo ""

echo "16. Cron Jobs (SSL Renewal)"
docker exec coffee_globe_nginx crontab -l
echo ""

echo "17. Application Version"
docker exec coffee_globe_php php artisan --version
echo ""

echo "18. Environment"
docker exec coffee_globe_php php artisan about | head -30
echo ""

echo "==================================="
echo "Verification Complete"
echo "==================================="
