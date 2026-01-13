.PHONY: help build up down restart logs shell-php shell-nginx shell-mysql composer-install composer-update artisan-migrate artisan-seed artisan-cache artisan-optimize ssl-setup ssl-renew clean backup restore ps status stop start pull rebuild diagnose

COMPOSE=docker compose
PHP_CONTAINER=coffee_globe_php
NGINX_CONTAINER=coffee_globe_nginx
MYSQL_CONTAINER=coffee_globe_mysql
REDIS_CONTAINER=coffee_globe_redis
DOMAIN=coffeeglobe.sa

help:
	@echo "Available commands:"
	@echo "  make build              - Build Docker images"
	@echo "  make up                 - Start all containers"
	@echo "  make down               - Stop all containers"
	@echo "  make start              - Start stopped containers"
	@echo "  make stop               - Stop running containers"
	@echo "  make restart            - Restart all containers"
	@echo "  make ps                 - Show container status"
	@echo "  make status             - Show detailed container status"
	@echo "  make logs               - Show logs from all containers"
	@echo "  make shell-php          - Open shell in PHP container"
	@echo "  make shell-nginx        - Open shell in Nginx container"
	@echo "  make shell-mysql        - Open shell in MySQL container"
	@echo "  make shell-redis        - Open shell in Redis container"
	@echo "  make composer-install   - Install PHP dependencies"
	@echo "  make composer-update    - Update PHP dependencies"
	@echo "  make artisan-migrate    - Run database migrations"
	@echo "  make artisan-seed       - Run database seeders"
	@echo "  make artisan-cache      - Clear all caches"
	@echo "  make artisan-optimize   - Optimize Laravel for production"
	@echo "  make ssl-init           - Initialize SSL certificates (first time)"
	@echo "  make ssl-setup          - Setup SSL certificates"
	@echo "  make ssl-renew          - Renew SSL certificates"
	@echo "  make ssl-enable-stapling - Enable SSL stapling (after certificates are obtained)"
	@echo "  make backup             - Backup database and files"
	@echo "  make restore            - Restore from backup"
	@echo "  make pull               - Pull latest images"
	@echo "  make rebuild            - Rebuild and restart containers"
	@echo "  make clean              - Remove containers and volumes"
	@echo "  make diagnose           - Run system diagnostic script"
	@echo "  make verify             - Verify complete system status"

build:
	$(COMPOSE) build --no-cache

up:
	$(COMPOSE) up -d
	@echo "Waiting for services to be ready..."
	@sleep 10
	@make artisan-migrate
	@make artisan-optimize

down:
	$(COMPOSE) down

start:
	$(COMPOSE) start

stop:
	$(COMPOSE) stop

restart:
	$(COMPOSE) restart

ps:
	$(COMPOSE) ps

status:
	@echo "=== Container Status ==="
	@$(COMPOSE) ps
	@echo ""
	@echo "=== PHP-FPM Status ==="
	@docker exec $(PHP_CONTAINER) php-fpm-healthcheck 2>/dev/null || echo "PHP-FPM health check not available"
	@echo ""
	@echo "=== Nginx Status ==="
	@docker exec $(NGINX_CONTAINER) nginx -t 2>&1 | head -1
	@echo ""
	@echo "=== MySQL Status ==="
	@docker exec $(MYSQL_CONTAINER) mysqladmin ping -h localhost -u root -p$${DB_ROOT_PASSWORD:-root_password} 2>/dev/null || echo "MySQL not responding"
	@echo ""
	@echo "=== Redis Status ==="
	@docker exec $(REDIS_CONTAINER) redis-cli ping 2>/dev/null || echo "Redis not responding"

logs:
	$(COMPOSE) logs -f

shell-php:
	$(COMPOSE) exec php bash

shell-nginx:
	$(COMPOSE) exec nginx sh

shell-mysql:
	$(COMPOSE) exec mysql bash

shell-redis:
	$(COMPOSE) exec redis sh

composer-install:
	$(COMPOSE) exec php composer install --no-dev --optimize-autoloader

composer-update:
	$(COMPOSE) exec php composer update --no-dev --optimize-autoloader

artisan-migrate:
	@echo "Running database migrations..."
	@$(COMPOSE) exec php php artisan migrate --force || (echo "Migration failed. Checking database connection..." && $(COMPOSE) exec php php artisan migrate:status || true)

artisan-seed:
	$(COMPOSE) exec php php artisan db:seed --force

artisan-cache:
	$(COMPOSE) exec php php artisan config:clear
	$(COMPOSE) exec php php artisan route:clear
	$(COMPOSE) exec php php artisan view:clear
	$(COMPOSE) exec php php artisan cache:clear

artisan-optimize:
	$(COMPOSE) exec php php artisan config:cache
	$(COMPOSE) exec php php artisan route:cache
	$(COMPOSE) exec php php artisan view:cache

ssl-init:
	@chmod +x docker/ssl/init-ssl.sh
	@./docker/ssl/init-ssl.sh

ssl-setup:
	@chmod +x docker/ssl/setup-ssl.sh
	@./docker/ssl/setup-ssl.sh

ssl-renew:
	@chmod +x docker/ssl/renew-ssl.sh
	@./docker/ssl/renew-ssl.sh

ssl-check:
	@echo "Checking SSL certificate status..."
	@docker exec $(NGINX_CONTAINER) certbot certificates || echo "No certificates found"

ssl-enable-stapling:
	@chmod +x docker/ssl/enable-ssl-stapling.sh
	@./docker/ssl/enable-ssl-stapling.sh

backup:
	@mkdir -p backups
	@echo "Backing up database..."
	@docker exec $(MYSQL_CONTAINER) mysqldump -u root -p$${DB_ROOT_PASSWORD:-root_password} --all-databases > backups/database_$$(date +%Y%m%d_%H%M%S).sql
	@echo "Backing up storage..."
	@tar -czf backups/storage_$$(date +%Y%m%d_%H%M%S).tar.gz storage/ server_storage/ 2>/dev/null || true
	@echo "Backup completed in backups/ directory"

restore:
	@echo "Usage: make restore DB_FILE=backups/database_YYYYMMDD_HHMMSS.sql STORAGE_FILE=backups/storage_YYYYMMDD_HHMMSS.tar.gz"
	@if [ -n "$$DB_FILE" ]; then \
		echo "Restoring database from $$DB_FILE..."; \
		docker exec -i $(MYSQL_CONTAINER) mysql -u root -p$${DB_ROOT_PASSWORD:-root_password} < $$DB_FILE; \
	fi
	@if [ -n "$$STORAGE_FILE" ]; then \
		echo "Restoring storage from $$STORAGE_FILE..."; \
		tar -xzf $$STORAGE_FILE; \
	fi

pull:
	$(COMPOSE) pull

rebuild:
	$(COMPOSE) down
	$(COMPOSE) build --no-cache
	$(COMPOSE) up -d
	@echo "Waiting for services to be ready..."
	@sleep 15
	@echo "Checking service health..."
	@for i in 1 2 3 4 5; do \
		if docker exec $(PHP_CONTAINER) php-fpm-healthcheck 2>/dev/null; then \
			echo "PHP-FPM is healthy"; \
			break; \
		fi; \
		echo "Waiting for PHP-FPM... (attempt $$i/5)"; \
		sleep 5; \
	done
	@make artisan-migrate || echo "Migration completed with warnings. Check logs if needed."
	@make artisan-optimize || echo "Optimization completed with warnings."

clean:
	$(COMPOSE) down -v
	docker system prune -f

clean-mysql:
	@echo "Stopping MySQL container..."
	@$(COMPOSE) stop mysql || true
	@echo "Removing MySQL volume..."
	@docker volume rm coffee_globe_mysql_data 2>/dev/null || echo "Volume does not exist or already removed"
	@echo "MySQL volume removed. Run 'make up' to recreate with fresh database."

reset-mysql: clean-mysql
	@echo "Starting MySQL with fresh database..."
	@$(COMPOSE) up -d mysql
	@echo "Waiting for MySQL to initialize..."
	@sleep 15
	@echo "MySQL initialized. Run 'make up' to start all services."

diagnose:
	@chmod +x docker/diagnose.sh
	@./docker/diagnose.sh

verify:
	@chmod +x VERIFY.sh
	@./VERIFY.sh
