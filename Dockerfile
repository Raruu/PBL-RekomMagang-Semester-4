# Build Stage
FROM node:18-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# PHP Dependencies Stage
FROM composer:latest AS composer
WORKDIR /app

# Copy composer files first for better caching
COPY composer.* ./

# Copy the rest of the application
COPY . .

# Install required PHP extensions for composer
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    intl \
    zip \
    mbstring \
    pdo \
    pdo_mysql \
    gd

# Install composer dependencies and generate autoloader
RUN composer install --optimize-autoloader --no-scripts --no-progress

# Final Stage
FROM php:8.2-fpm-alpine
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    openssl

# Install PHP extensions and dependencies
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    opcache \
    intl \
    zip \
    mbstring \
    gd \
    && apk del $PHPIZE_DEPS

# Copy application files
COPY . /var/www/html

# Copy built assets and vendor files
COPY --from=node-builder /app/public/build /var/www/html/public/build
COPY --from=composer /app/vendor /var/www/html/vendor/

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/ssl-cert.sh /usr/local/bin/ssl-cert.sh

# Make the SSL certificate generation script executable
RUN chmod +x /usr/local/bin/ssl-cert.sh

# Create required directories
RUN mkdir -p /etc/nginx/ssl \
    && mkdir -p /var/log/supervisor \
    && mkdir -p /var/log/nginx \
    && chown -R www-data:www-data /var/log/supervisor \
    && chown -R www-data:www-data /var/log/nginx

# Expose ports
EXPOSE 80 443

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]