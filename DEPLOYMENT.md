# Coffee Globe - Production Deployment

## SSL Certificate Management

### Initial Setup
```bash
cd /opt/coffee_globe
git pull
chmod +x setup.sh
./setup.sh
```

### SSL Auto-Renewal
- Certificates renew automatically via cron (3 AM daily)
- Cron job: `/usr/bin/certbot renew --quiet --no-self-upgrade`
- Renewal hook: `/etc/letsencrypt/renewal-hooks/deploy/certbot-renewal.sh`
- Certificates copied to: `docker/nginx/ssl/`

### Manual Renewal
```bash
make ssl-renew
```

### Check Certificate Status
```bash
make ssl-check
docker exec coffee_globe_nginx certbot certificates
```

## Database Connection

### Configuration (.env)
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=coffee_globe
DB_USERNAME=coffee_globe_user
DB_PASSWORD=K8#mP9vL2@nQ5&wR7!xT4*yU6^zA1bN3#jK7
DB_ROOT_PASSWORD=Q3#bN8jM5@kP2&wX9!vL7*yR4^tS6hM9
```

### Docker Compose Environment Variables
- Variables from `.env` are automatically loaded
- MySQL container uses these for initialization
- PHP container receives same variables for Laravel connection

### Verify Database Connection
```bash
docker exec coffee_globe_php php artisan db:show
docker exec coffee_globe_mysql mysql -u coffee_globe_user -p -e "SELECT 1"
```

## Certificate Lifecycle

### 1. First Time Setup (setup.sh)
1. Creates temporary self-signed certificate
2. Starts nginx with temp certificate
3. Requests Let's Encrypt certificate via certbot webroot
4. Replaces temp certificate with real certificate
5. Reloads nginx

### 2. Automatic Renewal (cron daily)
1. Certbot checks certificate expiry
2. If < 30 days, renews automatically
3. Runs deployment hook: `certbot-renewal.sh`
4. Hook copies new certificates using `cat` (not `cp` to avoid symlink issues)
5. Hook reloads nginx

### 3. Future Deployments
- Certificates persist in `docker/nginx/ssl/`
- No manual intervention needed
- Renewal happens automatically

## Troubleshooting

### Check SSL Certificate
```bash
openssl x509 -in docker/nginx/ssl/fullchain.pem -text -noout | grep -A2 "Validity"
```

### View Renewal Logs
```bash
docker exec coffee_globe_nginx cat /var/log/cron/certbot-renew.log
docker logs coffee_globe_nginx --tail 100 | grep certbot
```

### Database Connection Issues
```bash
docker exec coffee_globe_mysql mysql -u root -pQ3#bN8jM5@kP2&wX9!vL7*yR4^tS6hM9 -e "SHOW DATABASES;"
docker exec coffee_globe_php php artisan tinker --execute="DB::connection()->getPdo();"
```

### Restart Services
```bash
docker compose restart nginx
docker compose restart php
docker compose restart
```

## Security Notes

1. ✅ SSL certificates auto-renew
2. ✅ Database passwords from .env
3. ✅ Redis password protected
4. ✅ Session encryption enabled
5. ✅ HTTPS enforced
6. ✅ Strong password defaults in .env.example

## Files Modified for SSL Fix

- `docker-compose.yml`: Added `certbot_webroot` volume
- `docker/nginx/conf.d/coffeeglobe.sa.conf`: Changed acme-challenge root to `/var/www/certbot`
- `docker/ssl/init-ssl.sh`: Uses `cat` instead of `docker cp`
- `docker/ssl/setup-ssl.sh`: Uses `cat` instead of `docker cp`
- `docker/ssl/certbot-renewal.sh`: Uses `cat` instead of `cp`
- `docker/ssl/renew-ssl.sh`: Uses `cat` instead of `docker cp`
- `setup.sh`: Complete deployment script

## Why `cat` Instead of `cp/docker cp`?

Let's Encrypt creates symlinks:
```
/etc/letsencrypt/live/coffeeglobe.sa/fullchain.pem -> ../../archive/coffeeglobe.sa/fullchain1.pem
```

Using `cp` or `docker cp` copies the symlink, not the actual file.
Using `cat` reads and writes the actual certificate content.
