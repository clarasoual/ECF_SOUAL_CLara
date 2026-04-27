FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data

RUN sed -i 's/^#LoadModule mpm_prefork/LoadModule mpm_prefork/' /etc/apache2/apache2.conf \
    && a2dismod mpm_event || true \
    && a2enmod mpm_prefork rewrite || true