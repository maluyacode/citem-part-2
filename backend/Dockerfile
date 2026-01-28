FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
  && docker-php-ext-install pdo_mysql mbstring zip exif bcmath xml gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy ALL source first (safer for Laravel)
COPY . .

# Prevent memory issues
ENV COMPOSER_MEMORY_LIMIT=-1

# Install dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Permissions (Render ignores chown but keep safe)
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/app/public bootstrap/cache \
  && chmod -R 0777 storage bootstrap/cache || true

EXPOSE 8000

CMD php -S 0.0.0.0:8000 -t public
