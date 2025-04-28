FROM php:8.4.2-apache-bullseye-slim

RUN a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html