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

# Ensure target storage directories exist
mkdir -p /var/www/html/storage/app/public/products

# Copy seeded images from scratch folder into public storage dynamically on boot
find /var/www/html/scratch/drive_download/Categories -type f \( -iname "*.jpg" -o -iname "*.png" -o -iname "*.jpeg" \) -exec cp {} /var/www/html/storage/app/public/products/ \;
if [ -f "/var/www/html/scratch/corporate_gift_hamper.png" ]; then
    cp /var/www/html/scratch/corporate_gift_hamper.png /var/www/html/storage/app/public/products/
fi

# Re-create Laravel storage symlink dynamically on boot
php artisan storage:link --force

# Set proper ownership and permissions for copied storage files
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Run database migrations automatically in production
php artisan migrate --force

# Start Apache web server in the foreground
exec apache2-foreground
