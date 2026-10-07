FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends curl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY ./src/ /var/www/html/

EXPOSE 80