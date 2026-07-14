# --- Stage 1: Build Assets ---
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package.json ./
RUN npm install
COPY . .
RUN npm run build

# --- Stage 2: PHP Application ---
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    icu-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql bcmath mbstring zip intl gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy compiled assets from Stage 1
COPY --from=frontend-builder ./app/public/build ./public/build

# Install PHP dependencies (production setup)
RUN composer install --no-interaction --optimize-autoloader --ignore-platform-reqs

RUN php artisan vendor:publish --all

# Set permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
