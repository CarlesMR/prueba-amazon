FROM php:8.3-fpm

RUN apt-get update && apt-get install -y git unzip libzip-dev \
    && docker-php-ext-install zip opcache

RUN pecl install mongodb && docker-php-ext-enable mongodb

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
