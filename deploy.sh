#!/usr/bin/env bash
set -e

echo "Starting deployment..."

# Démarrer PHP-FPM en arrière-plan
php-fpm -D

# Attendre quelques secondes
sleep 3

echo "Running Symfony cache warmup..."
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

echo "Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

echo "Starting Nginx..."
nginx -g 'daemon off;'
