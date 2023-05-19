FROM php:8.1-fpm-alpine

WORKDIR /var/www/app

ARG TZ=Asia/Tokyo
ARG PSYSH_DIR=/usr/local/share/psysh
ARG PHP_MANUAL_URL=http://psysh.org/manual/ja/php_manual.sqlite

RUN set -eux && \
    apk update && \
    apk add --no-cache \
        freetype \
        libjpeg-turbo \
        libjpeg-turbo-utils \
        libpng \
        libwebp \
        libxpm \
        oniguruma-dev \
        icu-dev \
        && \
    apk add --no-cache --virtual=.build-dependencies \
        linux-headers \
        autoconf \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libxpm-dev \
        gcc \
        g++ \
        make \
        tzdata \
        && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    echo ${TZ} > /etc/timezone && \
    pecl install xdebug && \
    docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
        --with-xpm \
        && \
    docker-php-ext-install -j$(nproc) intl mbstring pdo_mysql gd && \
    docker-php-ext-enable xdebug && \
    apk del .build-dependencies && \
    mkdir $PSYSH_DIR && wget $PHP_MANUAL_URL -P $PSYSH_DIR

RUN curl -L https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v3.9.5/php-cs-fixer.phar -o php-cs-fixer && \
    chmod a+x php-cs-fixer && \
    mv php-cs-fixer /usr/local/bin/php-cs-fixer

COPY php.ini /usr/local/etc/php