FROM php:7.2-fpm
RUN apt-get update && apt-get install -y libmcrypt-dev libcurl4-gnutls-dev mysql-client

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# lumen packages
RUN pecl install mcrypt-1.0.1 \
    && docker-php-ext-install curl pdo pdo_mysql mysqli \
    && docker-php-ext-enable curl mcrypt pdo pdo_mysql mysqli

ADD ./init.sh /var/init.sh
RUN chmod +x /var/init.sh
