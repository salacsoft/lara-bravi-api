FROM php:7.4-fpm-alpine

WORKDIR /var/www/html

RUN  docker-php-ext-install pdo  pdo_mysql

ADD . /var/www/html

RUN chown -R $USER:www-data /var/www/html/storage

RUN chown -R $USER:www-data /var/www/html

RUN chown -R $USER:www-data /var/www/html/bootstrap/cache

RUN chmod -R 775 /var/www/html/storage

RUN chmod -R 775 /var/www/html/bootstrap/cache


