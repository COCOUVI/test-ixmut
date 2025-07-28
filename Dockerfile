### Étape 1 : Build des assets frontend avec Vite
FROM node:18 AS node-builder

WORKDIR /app
COPY package*.json ./
RUN npm install

COPY resources ./resources
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./
COPY public ./public
COPY .env.example .env

RUN npm run build

---

### Étape 2 : Backend Laravel avec PHP-FPM
FROM php:8.2-fpm

WORKDIR /var/www

# Dépendances système
RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le backend Laravel
COPY . .

# Copier les assets buildés par Vite
COPY --from=node-builder /app/public/build /var/www/public/build

# Droits corrects (important sur Railway ou Render)
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Clé Laravel
RUN cp .env.example .env && php artisan key:generate

# Exposer le port
EXPOSE 8000

# Lancer les migrations + serveur PHP
CMD php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
