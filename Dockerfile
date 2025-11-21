# Étape 1 : Build
FROM php:8.3-fpm-alpine AS build

RUN apk add --no-cache \
    git zip unzip libpq-dev icu-dev libzip-dev oniguruma-dev bash nginx \
    && docker-php-ext-install intl pdo pdo_pgsql opcache zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN echo "APP_ENV=prod" > .env
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction

# Étape 2 : Image finale
FROM php:8.3-fpm-alpine

# 👉 Installer de nouveau les libs et extensions PHP nécessaires
RUN apk add --no-cache \
    nginx bash libpq-dev icu-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install intl pdo pdo_pgsql opcache zip

# Copier Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le code
COPY --from=build /var/www/html /var/www/html
COPY .render/nginx.conf /etc/nginx/http.d/default.conf
COPY deploy.sh /usr/local/bin/deploy.sh
RUN chmod +x /usr/local/bin/deploy.sh

EXPOSE 10000
CMD ["/usr/local/bin/deploy.sh"]
