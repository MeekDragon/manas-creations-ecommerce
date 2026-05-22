# Base Image
FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql pdo_pgsql opcache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache virtual host
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Copy application files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies (exclude dev dependencies, optimize autoloader)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions for storage and bootstrap cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Expose port (Render sets PORT env dynamically)
EXPOSE 80

# Configure entrypoint script
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
