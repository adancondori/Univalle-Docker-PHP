FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
        libpq-dev \
        && docker-php-ext-install pdo_mysql
