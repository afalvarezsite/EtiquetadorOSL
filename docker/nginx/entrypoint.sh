#!/bin/sh

# Ensure the pdf directory and raid sub-directory exist
mkdir -p /var/www/html/public/pdf/raid

# Set permissions and ownership
# Note: In the official php:fpm image, the user is www-data (uid 33)
chown -R www-data:www-data /var/www/html/public/pdf
chmod -R 777 /var/www/html/public/pdf

# Execute the original command (php-fpm)
exec "$@"
