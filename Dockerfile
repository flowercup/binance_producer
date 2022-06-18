FROM php:7.4-cli

RUN mkdir -p /home/www-data && \
    chown www-data:www-data /home/www-data
COPY --chown=www-data:www-data ./app /application
RUN docker-php-ext-install sockets
WORKDIR /application

USER www-data

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN /usr/local/bin/composer install
CMD php bin/listener-server.php

