### Step 1: Build frontend with Vite
FROM node:18 AS node-builder

WORKDIR /app
COPY . .

RUN npm install && npm run build


### Step 2: PHP for Laravel backend with MySQL
FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy backend code
COPY . /var/www
COPY --chown=www-data:www-data . /var/www

# Copy built frontend assets
COPY --from=node-builder /app/public/build /var/www/public/build

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate app key
RUN cp .env.example .env && php artisan key:generate

# Expose port (Railway injecte automatiquement $PORT)
EXPOSE 8000

# Start app + migrations une fois DB connectée
CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
