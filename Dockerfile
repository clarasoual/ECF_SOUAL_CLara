FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql \
    && a2dismod mpm_event \
    && a2enmod mpm_prefork \
    && a2enmod rewrite