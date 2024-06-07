########################################################################
### Composer

FROM composer:2.6.2 AS composer

COPY composer.json /app
COPY composer.lock /app

RUN composer install        \
    --ignore-platform-reqs  \
    --no-ansi               \
    --no-autoloader         \
    --no-interaction        \
    --no-scripts

COPY . /app/
RUN composer dump-autoload --optimize --classmap-authoritative

### Composer
########################################################################
### PHP

FROM php:8.1-fpm-alpine3.18

RUN apk update && apk add --no-cache \
    libpng-dev                       \
    freetype-dev                     \
    oniguruma-dev                    \
    libxml2-dev                      \
    icu-dev                          \
    openssh-client

RUN rm -rf /var/lib/apt/lists/* && rm -rf /var/cache/apk/*
RUN docker-php-ext-configure gd --enable-gd --with-freetype
RUN docker-php-ext-install pdo pdo_mysql gd opcache bcmath exif mbstring intl ctype session simplexml

COPY . /var/www
COPY --from=composer /app/vendor       /var/www/html/vendor
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www-data

COPY --chown=www:www-data . /var/www

WORKDIR /var/www

### PHP
########################################################################