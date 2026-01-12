# Docker Production Setup - Coffee Globe

## Requirements
- Docker 29.1.4+
- Docker Compose v5.0.1+
- Make (optional)

## Quick Start

1. Configure environment variables in `.env`:
```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=coffee_globe
DB_USERNAME=coffee_globe
DB_PASSWORD=your_password
DB_ROOT_PASSWORD=root_password

APP_URL=https://coffeeglobe.sa
APP_ENV=production
APP_DEBUG=false

REDIS_HOST=redis
REDIS_PORT=6379
```

2. Build and start containers:
```bash
make build
make up
```

3. Setup SSL certificates:
```bash
make ssl-setup
```

## Available Make Commands

### Basic Commands
- `make build` - Build Docker images
- `make up` - Start all containers
- `make down` - Stop all containers
- `make start` - Start stopped containers
- `make stop` - Stop running containers
- `make restart` - Restart all containers
- `make ps` - Show container status
- `make status` - Show detailed container status
- `make logs` - Show logs from all containers

### Shell Access
- `make shell-php` - Open shell in PHP container
- `make shell-nginx` - Open shell in Nginx container
- `make shell-mysql` - Open shell in MySQL container
- `make shell-redis` - Open shell in Redis container

### Composer & Artisan
- `make composer-install` - Install PHP dependencies
- `make composer-update` - Update PHP dependencies
- `make artisan-migrate` - Run database migrations
- `make artisan-seed` - Run database seeders
- `make artisan-cache` - Clear all caches
- `make artisan-optimize` - Optimize Laravel for production

### SSL Management
- `make ssl-setup` - Setup SSL certificates
- `make ssl-renew` - Renew SSL certificates

### Backup & Restore
- `make backup` - Backup database and files
- `make restore DB_FILE=backups/database_YYYYMMDD_HHMMSS.sql STORAGE_FILE=backups/storage_YYYYMMDD_HHMMSS.tar.gz` - Restore from backup

### Maintenance
- `make pull` - Pull latest images
- `make rebuild` - Rebuild and restart containers
- `make clean` - Remove containers and volumes

## File Storage

Media files are stored in `server_storage/media` on the host server, not inside containers. This ensures:
- Files persist across container restarts
- Easy file management and backup
- Direct server access to files

All storage directories have proper permissions (775) set automatically:
- `storage/` - Laravel storage
- `server_storage/media/` - Media files
- `bootstrap/cache/` - Cache files
- Logs in volumes for easy access

## Bind Mounts Configuration

### Nginx Volumes
- Application code: Read-only
- Storage: Read-only (serves files)
- Server storage: Read-only (serves media)
- Logs: Read-write (for log files)
- SSL certificates: Read-only

### PHP Volumes
- Application code: Read-write
- Storage directories: Read-write (for uploads, cache, logs)
- Server storage: Read-write (for media uploads)
- Configuration files: Read-only
- Logs volume: Read-write

### Database & Redis
- Data volumes: Persistent storage
- Log volumes: For log files

## Database Migrations

Migrations run automatically on container startup. The entrypoint script:
- Waits for MySQL to be ready
- Runs migrations
- Runs seeders (with duplicate checking)
- Creates storage symlink
- Caches configuration

## SSL Certificates

SSL certificates are managed via certbot and stored in `docker/nginx/ssl/`. The setup script:
- Checks for existing certificates
- Validates expiration (renews if < 30 days)
- Obtains new certificates if needed
- Automatically configures nginx

Certificate files are mounted from host to container for persistence.

## Performance Optimizations

### Nginx
- FastCGI caching enabled
- Gzip compression
- File caching
- Optimized buffer sizes
- HTTP/2 support

### PHP-FPM
- Dynamic process management (10-50 workers)
- OPcache enabled
- Increased memory limits
- Optimized timeouts

### MySQL
- Optimized buffer pool
- Query cache enabled
- Connection pooling

## Production Deployment

1. Ensure domain DNS points to server
2. Set environment variables in `.env`
3. Run `make build && make up`
4. Run `make ssl-setup`
5. Verify application at https://coffeeglobe.sa
6. Monitor with `make status`

## Nginx Configuration

The nginx configuration file is named `coffeeglobe.sa.conf` for easy identification and modification. It includes:
- HTTP and HTTPS server blocks
- FastCGI caching
- Security headers
- Optimized static file serving
- Media and storage aliases

## Troubleshooting

### Permission Issues
All permissions are set automatically in the Dockerfile and entrypoint script. If you encounter issues:
```bash
make shell-php
chmod -R 775 storage bootstrap/cache server_storage
```

### View Logs
```bash
make logs
# Or specific service
docker logs coffee_globe_php
docker logs coffee_globe_nginx
```

### Check Status
```bash
make status
```

### Rebuild Everything
```bash
make rebuild
```
