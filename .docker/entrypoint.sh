#!/bin/bash

# Set default port to 80 if PORT is not set
PORT=${PORT:-80}

# Update Apache listen port dynamically to match Render's assigned port
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/000-default.conf

# Clear and rebuild Laravel production caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations automatically in production
php artisan migrate --force

# Start Apache web server in the foreground
exec apache2-foreground
