ARG BASE_DIR=/var/www/html

# FROM composer:latest AS deps-install-base

# ARG BASE_DIR
# ARG IS_DEV

# COPY . $BASE_DIR
# RUN cd $BASE_DIR && composer install

FROM php:8.3-apache

ARG BASE_DIR

# COPY --from=deps-install-base $BASE_DIR/ow_libraries/vendor $BASE_DIR

RUN apt-get update && apt-get install -y \
        cron \
        libjpeg-dev \
        libfreetype6-dev \
        libpng-dev \
        libssl-dev \
        libonig-dev \
        libzip-dev \
        libicu-dev \
        ssmtp \
        zip && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr && \
    docker-php-ext-configure intl && \
    docker-php-ext-install gd mbstring pdo_mysql zip ftp intl && \
    a2enmod rewrite && a2enmod headers

CMD ["apache2-foreground"]
