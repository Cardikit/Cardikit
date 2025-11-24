#!/bin/bash
set -e

# Ensure storage directories exist and are writable even when host bind mounts override ownership.
mkdir -p /var/www/html/public/qrcodes
mkdir -p /var/www/html/public/images
chown -R www-data:www-data /var/www/html/public/qrcodes /var/www/html/public/images
chmod -R 775 /var/www/html/public/qrcodes /var/www/html/public/images

exec docker-php-entrypoint "$@"
