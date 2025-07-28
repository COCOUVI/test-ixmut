# --- Étape 2 : Backend Laravel avec PHP-FPM ---
FROM php:8.2-fpm

WORKDIR /var/www

# Dépendances système
RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers Laravel (exclure node_modules et public/build)
COPY --exclude=node_modules --exclude=public/build . .

# Copier les assets buildés par Vite APRÈS
COPY --from=node-builder /app/public/build /var/www/public/build

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Droits corrects APRÈS avoir copié tous les fichiers
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Générer la clé Laravel
RUN cp .env.example .env && php artisan key:generate

EXPOSE 8000

CMD php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}