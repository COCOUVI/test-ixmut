FROM php:8.2-fpm

WORKDIR /var/www

# Dépendances système
RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'app
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Compiler les assets si Vite est utilisé
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install && npm run build

# Donner les bons droits
RUN chmod -R 775 storage bootstrap/cache

# Exposer le port 8080 pour Railway
EXPOSE 8080

# Lancer Laravel sur le port 8080 via le serveur interne
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080
