FROM php:7.2-fpm
RUN apt-get update && apt-get install -y libmcrypt-dev

# lumen packages
RUN pecl install redis-4.0.1 \
    && pecl install xdebug-2.6.0 \
    && docker-php-ext-enable redis xdebug
