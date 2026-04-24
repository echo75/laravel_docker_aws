FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libzip-dev netcat-openbsd \
    libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-install \
    pdo pdo_mysql zip mbstring xml bcmath intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# ensure directories exist (safe for image layer)
RUN mkdir -p \
    storage/logs \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

# do NOT rely on this for production (volume overrides it anyway)
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

CMD ["php-fpm"]
