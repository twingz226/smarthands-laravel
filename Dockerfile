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
RUN printf 'APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx=\nAPP_URL=http://localhost\n' > .env

# Create directories and set permissions
RUN mkdir -p bootstrap/cache storage/framework/sessions storage/framework/views storage/framework/cache storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Create entrypoint script
RUN printf '#!/bin/sh\nset -e\necho "==> Preparing environment..."\ncd /var/www/html\nrm -f .env\nLISTEN_PORT="${PORT:-80}"\necho "==> Nginx will listen on port $LISTEN_PORT"\nsed -i "s/PORT_PLACEHOLDER/$LISTEN_PORT/g" /etc/nginx/http.d/default.conf\necho "DB Check: Connection=$DB_CONNECTION Host=$DB_HOST Database=$DB_DATABASE"\nmkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache\nchown -R www-data:www-data storage bootstrap/cache\nchmod -R 775 storage bootstrap/cache\nphp artisan migrate --force --no-interaction || echo "Migration warning"\nphp artisan config:cache\nphp artisan route:cache\nphp artisan view:cache\nphp artisan storage:link --force 2>/dev/null || true\necho "==> Testing Nginx config..."\nnginx -t\necho "==> Starting Supervisord..."\nexec /usr/bin/supervisord -c /etc/supervisord.conf\n' > /entrypoint.sh \
    && chmod +x /entrypoint.sh

# Expose port (Railway overrides this with PORT env var)
EXPOSE 80

CMD ["/entrypoint.sh"]
