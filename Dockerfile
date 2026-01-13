FROM php:8.2-fpm

ENV DEBIAN_FRONTEND=noninteractive
ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_MEMORY_LIMIT=256
ENV PHP_OPCACHE_MAX_ACCELERATED_FILES=20000
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
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

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    intl \
    mbstring \
    exif \
    pcntl \
    bcmath \
    opcache \
    pdo \
    pdo_mysql \
    soap \
    sockets

RUN pecl install redis imagick \
    && docker-php-ext-enable redis imagick

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /var/www/html \
    /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache \
    /var/www/html/server_storage/media \
    /var/log/php \
    /var/cache/nginx

RUN chown -R www-data:www-data \
    /var/www/html \
    /var/log/php \
    /var/cache/nginx

RUN chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/server_storage \
    /var/log/php \
    /var/cache/nginx

WORKDIR /var/www/html

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/custom.conf
COPY docker/entrypoint.sh /docker-entrypoint.sh
COPY docker/php/php-fpm-healthcheck /usr/local/bin/php-fpm-healthcheck

RUN chmod +x /docker-entrypoint.sh \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

EXPOSE 9000

ENTRYPOINT ["/docker-entrypoint.sh"]
