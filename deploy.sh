#!/usr/bin/env bash
set -e

echo "Starting deployment..."

# Start PHP-FPM in the background
php-fpm -D
sleep 3

echo "Running composer scripts..."
composer run-script post-install-cmd --no-dev --no-interaction || true

echo "Clearing and warming cache..."
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

echo "Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

echo "Starting Nginx..."
nginx -g 'daemon off;'
