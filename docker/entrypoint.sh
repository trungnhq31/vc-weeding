#!/bin/bash
set -e

echo "Starting container setup..."

# Copy .env.docker to .env if .env does not exist
if [ ! -f "/var/www/html/.env" ]; then
    echo "Creating .env from .env.docker..."
    cp /var/www/html/.env.docker /var/www/html/.env
fi

# Ensure storage and cache directory permissions
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Run composer install if vendor doesn't exist
if [ ! -d "/var/www/html/vendor" ]; then
    echo "Running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Generate application key if missing in .env
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Create storage symlink if not exists
if [ ! -L "/var/www/html/public/storage" ]; then
    echo "Creating storage symlink..."
    php artisan storage:link || true
fi

# Execute main command (e.g. php-fpm, queue:work, reverb:start, npm run dev)
exec "$@"
