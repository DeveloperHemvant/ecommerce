FROM php:8.2-fpm

WORKDIR /var/www/html

# Required packages & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libonig-dev libicu-dev \
    nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        bcmath \
        intl \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cache dependencies first
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-interaction

COPY package*.json ./
RUN npm install

# Copy source
COPY . .

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]