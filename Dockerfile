FROM php:8.2-fpm

ENV DEBIAN_FRONTEND=noninteractive
ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_MEMORY_LIMIT=256
ENV PHP_OPCACHE_MAX_ACCELERATED_FILES=20000
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0

# Install system dependencies (grouped for better caching)
# libwebp-dev required for GD WebP support (Intervention/MediaLibrary default_image_format)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libmagickwand-dev \
    libfcgi-bin \
    git \
    unzip \
    curl \
    ca-certificates \
    gosu \
    && rm -rf /var/lib/apt/lists/*

# Install core PHP extensions (fast ones first, grouped for better caching)
RUN docker-php-ext-install -j$(nproc) \
    opcache \
    pdo \
    pdo_mysql \
    bcmath

# Install mbstring (can be slow, separate layer)
RUN docker-php-ext-install -j$(nproc) mbstring

# Install intl (can be slow, separate layer)
RUN docker-php-ext-install -j$(nproc) intl

# Install zip (separate layer)
RUN docker-php-ext-install -j$(nproc) zip

# Install gd with WebP support (required for Intervention Image / media library)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd

# Install PECL extensions (separate layer for better caching)
RUN pecl install redis imagick \
    && docker-php-ext-enable redis imagick

# Install Composer (cached separately)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer

# Create directories and set permissions (combined for efficiency)
# Include storage/app/public/media for MediaLibrary when MEDIA_USE_STORAGE=true
RUN mkdir -p /var/www/html \
    /var/www/html/storage/app/public \
    /var/www/html/storage/app/public/media \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache \
    /var/www/html/server_storage/media \
    /var/www/html/public/media \
    /var/log/php \
    /var/cache/nginx \
    && chown -R www-data:www-data \
        /var/www/html \
        /var/log/php \
        /var/cache/nginx \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/server_storage \
        /var/log/php \
        /var/cache/nginx

WORKDIR /var/www/html

# Copy configuration files (done late to maximize cache hits)
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/custom.conf
COPY docker/entrypoint.sh /docker-entrypoint.sh
COPY docker/php/php-fpm-healthcheck /usr/local/bin/php-fpm-healthcheck

RUN chmod +x /docker-entrypoint.sh /usr/local/bin/php-fpm-healthcheck

EXPOSE 9000

ENTRYPOINT ["/docker-entrypoint.sh"]
