#!/bin/bash
set -e

# Ensure QR storage directory exists and is writable even when host bind mounts override ownership.
mkdir -p /var/www/html/public/qrcodes
chown -R www-data:www-data /var/www/html/public/qrcodes
chmod -R 775 /var/www/html/public/qrcodes

exec docker-php-entrypoint "$@"
