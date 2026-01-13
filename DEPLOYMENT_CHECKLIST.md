# 📋 Coffee Globe - Deployment Checklist

## ✅ Pre-Deployment Verification

### 1. Server Requirements
- [ ] Ubuntu 20.04+ or CentOS 8+
- [ ] Docker installed (version 20.10+)
- [ ] Docker Compose installed (version 2.0+)
- [ ] Git installed
- [ ] Ports 80 and 443 open in firewall
- [ ] Domain DNS configured pointing to server IP

### 2. Domain Configuration
- [ ] A record: `@` → Server IP
- [ ] A record: `www` → Server IP
- [ ] DNS propagation completed (check with `nslookup your-domain.com`)

### 3. Required Files
- [ ] `ENV_TEMPLATE.txt` exists
- [ ] `SERVER_DEPLOY.sh` exists and is executable
- [ ] `docker-compose.yml` is properly configured
- [ ] All Docker configuration files are present

## 🚀 Deployment Steps

### Step 1: Initial Setup
```bash
# Clone repository
git clone https://github.com/donia-shaker/coffee_globe.git /opt/coffee_globe
cd /opt/coffee_globe

# Make scripts executable
chmod +x SERVER_DEPLOY.sh VERIFY.sh setup.sh
```

### Step 2: Run Deployment Script
```bash
# Execute deployment
make deploy
# OR: ./SERVER_DEPLOY.sh
```

**Expected Results:**
- ✅ All containers running
- ✅ Database migrations completed
- ✅ Seeders executed
- ✅ Permissions set correctly
- ✅ Application optimized

### Step 3: Initialize SSL Certificates
```bash
# Setup SSL (requires domain DNS to be configured)
make ssl-init
```

**Expected Results:**
- ✅ Let's Encrypt certificates obtained
- ✅ SSL certificates installed
- ✅ HTTPS working

### Step 4: Verify Deployment
```bash
# Run verification script
make verify
```

**Check:**
- ✅ All containers healthy
- ✅ Database connection working
- ✅ Redis connection working
- ✅ SSL certificates valid
- ✅ Application accessible via HTTPS

## 🔍 Post-Deployment Checks

### 1. Container Health
```bash
docker compose ps
```
**Expected:** All containers should be "Up" and "healthy"

### 2. Application Access
```bash
# Test HTTP (should redirect to HTTPS)
curl -I http://coffeeglobe.sa

# Test HTTPS
curl -I https://coffeeglobe.sa
```
**Expected:** HTTP 200 OK response

### 3. Database Connection
```bash
docker exec coffee_globe_php php artisan db:show
```
**Expected:** Database connection details displayed

### 4. SSL Certificate
```bash
docker exec coffee_globe_nginx certbot certificates
```
**Expected:** Valid certificate with expiry date ~90 days from now

### 5. Permissions
```bash
docker exec coffee_globe_php ls -la storage/
```
**Expected:** Owner should be `www-data:www-data` with `775` permissions

### 6. Application Logs
```bash
docker logs coffee_globe_php --tail 50
docker logs coffee_globe_nginx --tail 50
```
**Expected:** No critical errors

## 🛠️ Common Issues & Solutions

### Issue 1: Containers Not Starting
**Symptom:** `docker compose ps` shows containers as "Exited"

**Solution:**
```bash
# Check logs
docker logs coffee_globe_php
docker logs coffee_globe_nginx

# Rebuild
docker compose down
docker compose build --no-cache
docker compose up -d
```

### Issue 2: Database Connection Failed
**Symptom:** "Access denied" or "Connection refused"

**Solution:**
```bash
# Verify database credentials in .env
docker exec coffee_globe_php cat .env | grep DB_

# Restart MySQL
docker compose restart mysql

# Wait for MySQL to be ready
sleep 10
docker exec coffee_globe_php php artisan migrate --force
```

### Issue 3: SSL Certificate Failed
**Symptom:** "Failed to obtain Let's Encrypt certificates"

**Solution:**
```bash
# Verify DNS
nslookup coffeeglobe.sa

# Check if port 80 is accessible
curl http://coffeeglobe.sa/.well-known/acme-challenge/test

# Retry SSL setup
make ssl-init
```

### Issue 4: Permission Denied
**Symptom:** "Permission denied" errors in logs

**Solution:**
```bash
docker exec coffee_globe_php chmod -R 775 storage bootstrap/cache server_storage
docker exec coffee_globe_php chown -R www-data:www-data storage bootstrap/cache server_storage
docker exec coffee_globe_php find storage -type d -exec chmod 775 {} \;
docker exec coffee_globe_php find storage -type f -exec chmod 664 {} \;
```

### Issue 5: 500 Internal Server Error
**Symptom:** Application returns HTTP 500

**Solution:**
```bash
# Check APP_KEY
docker exec coffee_globe_php cat .env | grep APP_KEY

# If empty, generate it
docker exec coffee_globe_php php artisan key:generate --force

# Clear all caches
docker exec coffee_globe_php php artisan config:clear
docker exec coffee_globe_php php artisan cache:clear
docker exec coffee_globe_php php artisan route:clear
docker exec coffee_globe_php php artisan view:clear
docker exec coffee_globe_php php artisan optimize

# Restart PHP-FPM
docker compose restart php
```

### Issue 6: Nginx 502 Bad Gateway
**Symptom:** Nginx returns 502 error

**Solution:**
```bash
# Check PHP-FPM status
docker exec coffee_globe_php php-fpm-healthcheck

# Restart PHP-FPM
docker compose restart php

# Check Nginx config
docker exec coffee_globe_nginx nginx -t

# View logs
docker logs coffee_globe_nginx --tail 100
docker logs coffee_globe_php --tail 100
```

## 📊 Monitoring Commands

```bash
# Real-time container status
watch -n 2 'docker compose ps'

# Follow all logs
docker compose logs -f

# Follow specific service logs
docker logs coffee_globe_php -f
docker logs coffee_globe_nginx -f

# Check resource usage
docker stats

# System diagnostics
make diagnose

# Complete verification
make verify
```

## 🔄 Update Procedure

```bash
# 1. Pull latest code
cd /opt/coffee_globe
git pull

# 2. Rebuild containers
docker compose down
docker compose build --no-cache
docker compose up -d

# 3. Run migrations (if any new ones)
docker exec coffee_globe_php php artisan migrate --force

# 4. Clear and rebuild caches
docker exec coffee_globe_php php artisan optimize

# 5. Verify
make verify
```

## 📝 Environment Variables Checklist

### Required in Production
- [x] `APP_NAME` - Application name
- [x] `APP_ENV=production` - Must be production
- [x] `APP_KEY` - Auto-generated (base64 encoded)
- [x] `APP_DEBUG=false` - Must be false in production
- [x] `APP_URL` - Your actual domain URL

### Database
- [x] `DB_CONNECTION=mysql`
- [x] `DB_HOST=mysql` - Docker service name
- [x] `DB_DATABASE=coffee_globe`
- [x] `DB_USERNAME` - Changed from default
- [x] `DB_PASSWORD` - Strong password set
- [x] `DB_ROOT_PASSWORD` - Strong password set

### Cache & Session
- [x] `CACHE_STORE=file` or `redis` for production
- [x] `SESSION_DRIVER=file` or `redis` for production
- [x] `REDIS_HOST=redis` - Docker service name
- [x] `REDIS_PASSWORD=` - Empty if no auth configured

### SSL
- [x] `SSL_DOMAIN` - Your domain
- [x] `SSL_EMAIL` - Valid email for Let's Encrypt

## 🎯 Success Criteria

Your deployment is successful when:

1. ✅ All containers are running and healthy
2. ✅ Application accessible via HTTPS with valid SSL
3. ✅ Database migrations completed without errors
4. ✅ Seeders executed successfully
5. ✅ No critical errors in logs
6. ✅ HTTP redirects to HTTPS
7. ✅ SSL certificate valid and auto-renewing
8. ✅ File permissions correct (www-data:www-data)
9. ✅ All services responding (PHP-FPM, Nginx, MySQL, Redis)
10. ✅ Application loading correctly in browser

## 📞 Emergency Rollback

If deployment fails critically:

```bash
# Stop all containers
docker compose down

# Restore from backup (if available)
make restore DB_FILE=backups/database_YYYYMMDD_HHMMSS.sql

# Or start fresh
docker compose down -v
docker volume prune -f
git pull
make deploy
```

---

**Last Updated:** $(date)
**Deployment Version:** Production v1.0
