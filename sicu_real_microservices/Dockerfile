FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/

EXPOSE 80

RUN echo "DirectoryIndex principal.php" >> /etc/apache2/apache2.conf

EXPOSE 10000
