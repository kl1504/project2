FROM node:22-alpine AS node-builder

WORKDIR /var/www/html

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run build

FROM php:8.4-fpm
# Install dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql zip exif pcntl \
    && docker-php-source delete \
    && pecl install -o -f redis \
    && docker-php-ext-enable redis
    
# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-interaction --prefer-dist --no-autoloader

COPY --chown=www-data:www-data . /var/www/html

COPY --from=node-builder --chown=www-data:www-data /var/www/html/public/build ./public/build

RUN composer dump-autoload --optimize \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm", "-F"]
  