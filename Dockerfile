FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install sockets pdo_mysql mbstring exif pcntl bcmath gd zip

WORKDIR /var/www

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json .
COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install

RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
