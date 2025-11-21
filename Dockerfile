# Étape 1 : Build PHP
FROM php:8.3-fpm-alpine AS build

# Installer extensions et outils requis
RUN apk add --no-cache \
    git zip unzip libpq-dev icu-dev libzip-dev oniguruma-dev bash nginx \
    && docker-php-ext-install intl pdo pdo_pgsql opcache zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Créer le répertoire de l'app
WORKDIR /var/www/html

# Copier les fichiers
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Étape 2 : Image finale avec Nginx
FROM php:8.3-fpm-alpine

# Installer nginx et dépendances
RUN apk add --no-cache nginx bash

# Copier le code
COPY --from=build /var/www/html /var/www/html

# Copier la config Nginx
COPY .render/nginx.conf /etc/nginx/http.d/default.conf

# Copier le script de déploiement
COPY deploy.sh /usr/local/bin/deploy.sh
RUN chmod +x /usr/local/bin/deploy.sh

# Exposer le port (Render utilise 10000)
EXPOSE 10000

# Commande de démarrage
CMD ["/usr/local/bin/deploy.sh"]
