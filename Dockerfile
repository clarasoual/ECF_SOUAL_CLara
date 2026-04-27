FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN find /etc/apache2/mods-enabled/ -name "mpm_*.load" -delete \
    && find /etc/apache2/mods-enabled/ -name "mpm_*.conf" -delete \
    && ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/ \
    && ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/