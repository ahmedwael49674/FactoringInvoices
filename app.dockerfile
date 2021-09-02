FROM php:7.4-fpm-alpine

ENV APP_ENV local
ENV APP_DEBUG 1

RUN apk add --update --no-cache \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-iconv \
    php7-json \
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-pdo_sqlite \
    php7-pdo_pgsql \
    php7-xml \
    php7-simplexml \
    php7-intl \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-memcached \
    php7-xmlwriter \
    php7-zip \
    curl \
    git \
    zip \
    unzip \
    openssh \
    busybox-extras \
    sudo

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && \
    chmod +x /usr/bin/composer

RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

RUN adduser -D -g 'www' www
RUN chown -R www.www /run

COPY . /var/www/

WORKDIR /var/www/

RUN composer install 

EXPOSE 8000