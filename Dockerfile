FROM php:8.3-fpm-alpine AS build
RUN apk add --no-cache \
    git zip unzip libpq-dev icu-dev libzip-dev oniguruma-dev bash nginx \
    && docker-php-ext-install intl pdo pdo_pgsql opcache zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Créer un .env minimal
RUN echo "APP_ENV=prod" > .env

# Installer dépendances sans exécuter de scripts
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction

# Étape 2 : Image finale
FROM php:8.3-fpm-alpine
RUN apk add --no-cache nginx bash

# Copier Composer depuis image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY --from=build /var/www/html /var/www/html
COPY .render/nginx.conf /etc/nginx/http.d/default.conf
COPY deploy.sh /usr/local/bin/deploy.sh
RUN chmod +x /usr/local/bin/deploy.sh

EXPOSE 10000
CMD ["/usr/local/bin/deploy.sh"]
