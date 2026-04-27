FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite \
    && a2dismod mpm_event \
    && a2enmod mpm_prefork

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/PROJET/UTILISATEUR\n\
    DirectoryIndex USR-index.php\n\
    <Directory /var/www/html/PROJET/UTILISATEUR>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

EXPOSE 80