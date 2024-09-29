FROM php:8.3-apache

RUN --mount=type=cache,target=/var/cache/apt \
    apt-get update && apt-get install -y \
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
    a2enmod rewrite && a2enmod headers && \
    service apache2 restart

ARG dir_to_copy='.'

COPY --chown=www-data:www-data ${dir_to_copy} /var/www/html
COPY --chown=www-data:www-data php.ini /usr/local/etc/php/conf.d/

RUN (crontab -l 2>/dev/null; echo '* * * * * curl localhost/ow_cron/run.php') | crontab - && cron

CMD ["apache2-foreground"]
