#!/bin/bash
set -e

echo "Starting container setup..."

# Ensure storage and cache directory permissions
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Run composer install if vendor doesn't exist
if [ ! -d "/var/www/html/vendor" ]; then
    echo "Running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Execute main command (php-fpm)
exec "$@"
