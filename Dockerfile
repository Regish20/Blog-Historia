FROM php:8.2-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY . /var/www/html/
ENTRYPOINT php -S 0.0.0.0:$PORT -t .
