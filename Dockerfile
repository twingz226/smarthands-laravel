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

# Create a minimal valid .env for build time only
# (Railway injects real env vars at runtime — template syntax like ${{...}} is NOT valid during build)
RUN printf 'APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx=\nAPP_URL=http://localhost\n' > .env

# Remove cached bootstrap files to avoid loading dev providers
RUN rm -rf bootstrap/cache/*.php

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Create entrypoint script (runs at container start when Railway env vars exist)
RUN printf '#!/bin/sh\n\
set -e\n\
\n\
cd /var/www/html\n\
\n\
# Remove build-time .env — Laravel reads from system environment in production\n\
rm -f .env\n\
\n\
# Ensure storage directories exist\n\
mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views\n\
chmod -R 775 storage bootstrap/cache\n\
\n\
# Run database migrations\n\
php artisan migrate --force 2>&1 || echo "Migration warning (may be OK on first run)"\n\
\n\
# Cache configuration (reads from system env vars injected by Railway)\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
php artisan storage:link 2>/dev/null || true\n\
\n\
echo "==> App ready, starting server..."\n\
exec /usr/bin/supervisord -c /etc/supervisord.conf\n\
' > /entrypoint.sh && chmod +x /entrypoint.sh

# Expose port
EXPOSE 80

# Start via entrypoint (NOT supervisord directly)
CMD ["/entrypoint.sh"]
