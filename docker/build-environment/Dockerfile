FROM php:7.4-fpm-alpine

RUN apk update \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-install sockets \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
