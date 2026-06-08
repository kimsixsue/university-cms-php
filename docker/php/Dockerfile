FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        unzip \
        zip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && a2enmod rewrite \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/app.conf \
    && a2enconf app

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html