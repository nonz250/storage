FROM php:7.4-fpm-alpine

WORKDIR /var/www/app

ARG TZ=Asia/Tokyo
ARG PSYSH_DIR=/usr/local/share/psysh
ARG PHP_MANUAL_URL=http://psysh.org/manual/ja/php_manual.sqlite

RUN set -eux && \
    apk update && \
    apk add --update --no-cache --virtual=.build-dependencies \
        autoconf \
        gcc \
        g++ \
        make \
        tzdata && \
    apk add --update --no-cache \
        oniguruma-dev \
        icu-dev && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    echo ${TZ} > /etc/timezone && \
    pecl install xdebug && \
    apk del .build-dependencies && \
    docker-php-ext-install intl mbstring && \
    docker-php-ext-enable xdebug && \
    mkdir $PSYSH_DIR && wget $PHP_MANUAL_URL -P $PSYSH_DIR

RUN curl -L https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v3.1.0/php-cs-fixer.phar -o php-cs-fixer && \
    chmod a+x php-cs-fixer && \
    mv php-cs-fixer /usr/local/bin/php-cs-fixer

COPY php.ini /usr/local/etc/php