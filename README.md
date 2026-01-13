# Coffee Globe - Laravel Application

A professional Laravel application for Coffee Globe business with Docker deployment, SSL certificates, and complete production setup.

## 🚀 Quick Start

### Prerequisites

- Docker & Docker Compose
- Git
- Make (optional, for using Makefile commands)

### 1. Clone Repository

```bash
git clone https://github.com/donia-shaker/coffee_globe.git
cd coffee_globe
```

### 2. Environment Setup

```bash
# Copy environment template
cp ENV_TEMPLATE.txt .env

# Generate application key
docker compose up -d php
docker exec coffee_globe_php php artisan key:generate --force
```

### 3. Start Application

```bash
# Start all services
docker compose up -d

# Wait for services to be ready (about 20 seconds)
sleep 20

# Run migrations and seeders
docker exec coffee_globe_php composer install --no-dev --optimize-autoloader
docker exec coffee_globe_php php artisan migrate:fresh --force
docker exec coffee_globe_php php artisan db:seed --force
docker exec coffee_globe_php php artisan storage:link
docker exec coffee_globe_php php artisan optimize

# Set proper permissions
docker exec coffee_globe_php chmod -R 775 storage bootstrap/cache
docker exec coffee_globe_php chown -R www-data:www-data storage bootstrap/cache
```

### 4. Access Application

- **HTTP**: http://localhost
- **HTTPS** (after SSL setup): https://your-domain.com

## 📦 Quick Deploy Script

For production deployment, use the automated script:

```bash
# Method 1: Direct script execution
chmod +x SERVER_DEPLOY.sh
./SERVER_DEPLOY.sh

# Method 2: Using Make command
make deploy
```

This script will:
- Create `.env` file with correct configuration
- Build and start all containers
- Install dependencies
- Run migrations and seeders
- Set up permissions
- Optimize the application

## 🔧 Available Make Commands

```bash
# Container Management
make up              # Start all containers
make down            # Stop all containers
make restart         # Restart all containers
make ps              # Show container status
make logs            # Show container logs

# Database Operations
make artisan-migrate # Run migrations
make artisan-seed    # Run seeders
make artisan-cache   # Clear caches
make artisan-optimize # Optimize application

# SSL Certificate Management
make ssl-init        # Initialize SSL certificates (first time)
make ssl-setup       # Setup/renew SSL certificates
make ssl-check       # Check SSL certificate status

# Deployment
make deploy          # Run complete deployment script
make verify          # Verify system status
make diagnose        # Run diagnostic script

# Utilities
make shell-php       # Enter PHP container
make shell-nginx     # Enter Nginx container
make shell-mysql     # Enter MySQL container
make composer-install # Install PHP dependencies
```

## 🏗️ Architecture

### Docker Services

- **nginx**: Web server with Let's Encrypt SSL support
- **php**: PHP 8.2 with Laravel application
- **mysql**: MySQL 8.4 database
- **redis**: Redis cache and session storage

### Port Mapping

- `80`: HTTP
- `443`: HTTPS
- `3306`: MySQL (exposed for development)
- `6379`: Redis (exposed for development)

## 🔐 SSL Certificate Setup

### Automatic SSL (Recommended)

For production with a valid domain:

```bash
# First time setup
make ssl-init

# Manual renewal (auto-renews via cron daily at 3 AM)
make ssl-renew
```

### SSL Configuration

Update `.env` with your domain:

```env
SSL_DOMAIN=your-domain.com
SSL_EMAIL=admin@your-domain.com
APP_URL=https://your-domain.com
```

## 🗄️ Database Configuration

### Default Credentials

```env
DB_DATABASE=coffee_globe
DB_USERNAME=coffee_globe_user
DB_PASSWORD=K8#mP9vL2@nQ5&wR7!xT4*yU6^zA1bN3#jK7
DB_ROOT_PASSWORD=Q3#bN8jM5@kP2&wX9!vL7*yR4^tS6hM9
```

### Database Operations

```bash
# Fresh migration
docker exec coffee_globe_php php artisan migrate:fresh --force

# Seed database
docker exec coffee_globe_php php artisan db:seed --force

# Check migration status
docker exec coffee_globe_php php artisan migrate:status

# Database connection test
docker exec coffee_globe_mysql mysql -u coffee_globe_user -pK8#mP9vL2@nQ5&wR7!xT4*yU6^zA1bN3#jK7 coffee_globe -e "SELECT 1"
```

## 📝 Environment Variables

Key environment variables (see `ENV_TEMPLATE.txt` for complete list):

```env
APP_NAME="Coffee Globe"
APP_ENV=production
APP_KEY=base64:u5UAvjCVYTpEVNcTBwFbUgCE4JFyko7FbY9tviT5EXg=
APP_DEBUG=false
APP_URL=https://coffeeglobe.sa

DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=coffee_globe

CACHE_STORE=file            # Use 'redis' for production
SESSION_DRIVER=file         # Use 'redis' for production
QUEUE_CONNECTION=database
```

## 🔨 Development

### Install Dependencies

```bash
# PHP dependencies
docker exec coffee_globe_php composer install

# NPM dependencies (if using Vite)
docker exec coffee_globe_php npm install
docker exec coffee_globe_php npm run build
```

### Laravel Commands

```bash
# Run artisan commands
docker exec coffee_globe_php php artisan [command]

# Examples:
docker exec coffee_globe_php php artisan tinker
docker exec coffee_globe_php php artisan route:list
docker exec coffee_globe_php php artisan config:clear
```

### Container Access

```bash
# PHP container
docker exec -it coffee_globe_php bash

# Nginx container
docker exec -it coffee_globe_nginx sh

# MySQL container
docker exec -it coffee_globe_mysql bash
```

## 🐛 Troubleshooting

### Check Container Status

```bash
docker compose ps
docker logs coffee_globe_php --tail 50
docker logs coffee_globe_nginx --tail 50
```

### Permission Issues

```bash
docker exec coffee_globe_php chmod -R 775 storage bootstrap/cache
docker exec coffee_globe_php chown -R www-data:www-data storage bootstrap/cache
```

### SSL Certificate Issues

```bash
# Check certificate
docker exec coffee_globe_nginx certbot certificates

# View nginx logs
docker logs coffee_globe_nginx --tail 100 | grep error

# Test nginx config
docker exec coffee_globe_nginx nginx -t
```

### Database Connection Issues

```bash
# Check database
docker exec coffee_globe_mysql mysql -u root -pQ3#bN8jM5@kP2&wX9!vL7*yR4^tS6hM9 -e "SHOW DATABASES;"

# Check Laravel database connection
docker exec coffee_globe_php php artisan db:show
```

### Clear All Caches

```bash
docker exec coffee_globe_php php artisan config:clear
docker exec coffee_globe_php php artisan cache:clear
docker exec coffee_globe_php php artisan route:clear
docker exec coffee_globe_php php artisan view:clear
```

### Rebuild Containers

```bash
# Complete rebuild
docker compose down
docker compose build --no-cache
docker compose up -d
```

## 📁 Project Structure

```
coffee_globe/
├── app/                    # Laravel application
├── bootstrap/              # Laravel bootstrap
├── config/                 # Configuration files
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── docker/
│   ├── nginx/             # Nginx configuration
│   ├── php/               # PHP configuration
│   ├── mysql/             # MySQL configuration
│   └── ssl/               # SSL scripts
├── public/                 # Public files
├── resources/              # Views, assets
├── routes/                 # Application routes
├── storage/                # Storage files
├── docker-compose.yml      # Docker services
├── Dockerfile              # PHP container
├── Makefile               # Make commands
├── ENV_TEMPLATE.txt       # Environment template
└── SERVER_DEPLOY.sh       # Deployment script
```

## 🔒 Security Notes

1. **Change Default Passwords**: Update all passwords in `.env` before production
2. **APP_KEY**: Auto-generated on first `php artisan key:generate`
3. **SSL Certificates**: Auto-renew via cron (3 AM daily)
4. **File Permissions**: Set via entrypoint script automatically
5. **Database Access**: MySQL port exposed only for development

## 🚀 Production Deployment

### Server Requirements

- Ubuntu 20.04+ / CentOS 8+
- Docker & Docker Compose installed
- Domain pointing to server IP
- Ports 80, 443 open in firewall

### Deployment Steps

```bash
# 1. Clone repository
git clone https://github.com/donia-shaker/coffee_globe.git /opt/coffee_globe
cd /opt/coffee_globe

# 2. Run deployment script
make deploy
# OR: chmod +x SERVER_DEPLOY.sh && ./SERVER_DEPLOY.sh

# 3. Setup SSL (after DNS is configured)
make ssl-init

# 4. Verify deployment
curl -I https://your-domain.com
docker compose ps
```

### DNS Configuration

Point your domain to the server:

```
A     @              YOUR_SERVER_IP
A     www            YOUR_SERVER_IP
```

## 📊 Monitoring

### Check Application Status

```bash
# Container health
docker compose ps

# Application logs
docker logs coffee_globe_php -f

# Nginx access logs
docker exec coffee_globe_nginx tail -f /var/log/nginx/access.log

# Nginx error logs
docker exec coffee_globe_nginx tail -f /var/log/nginx/error.log
```

## 🔄 Updates

```bash
# Pull latest changes
git pull

# Rebuild and restart
docker compose down
docker compose up -d --build

# Run migrations (if any)
docker exec coffee_globe_php php artisan migrate --force

# Clear and rebuild caches
docker exec coffee_globe_php php artisan optimize
```

## 📞 Support

- **Repository**: https://github.com/donia-shaker/coffee_globe
- **Issues**: https://github.com/donia-shaker/coffee_globe/issues

## 📜 License

This project is proprietary software. All rights reserved.

---

**Built with ❤️ by Coffee Globe Team**
