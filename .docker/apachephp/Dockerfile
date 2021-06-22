FROM php:8.0-apache
MAINTAINER Jgauthi <github.com/jgauthi>

# Some libs
RUN apt-get update && apt-get install -y --no-install-recommends vim curl locales apt-utils unzip

# https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions @composer \
    apcu opcache intl \
    pdo_sqlite \
    mbstring \
    redis \
    xdebug \
    zip

# PHP Conf
COPY php.ini /usr/local/etc/php/php.ini

COPY apache_default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers

WORKDIR /var/www/
USER www-data