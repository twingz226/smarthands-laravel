# Build stage for frontend assets
FROM node:18-alpine AS assets

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci

# Copy source files
COPY resources/ resources/
COPY webpack.mix.js ./

# Build assets for production
RUN npm run production

# Build stage for PHP dependencies
FROM php:8.3-cli-alpine AS vendor

WORKDIR /app

# Install system dependencies for GD and zip
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer files
COPY composer.* ./

# Install dependencies without dev packages
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Final production image
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy application code
COPY . /var/www/html

# Copy built assets from assets stage
COPY --from=assets /app/public /var/www/html/public

# Copy vendor dependencies from vendor stage
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copy configuration files
COPY nginx.conf /etc/nginx/http.d/default.conf
COPY supervisord.conf /etc/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Create .env file from example
RUN cp .env.example .env

# Generate application key
RUN php artisan key:generate --force

# Optimize Laravel for production
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 80

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
