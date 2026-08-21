FROM php:8.2-fpm

WORKDIR /var/www/html

# Packages + extensions
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libonig-dev libicu-dev nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath intl gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy entire project FIRST
COPY . .

# Install composer deps
RUN composer install --prefer-dist --no-interaction --optimize-autoloader

# Install node deps (if needed)
RUN npm install && npm run build

RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]