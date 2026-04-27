FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

# Copier TOUT le repo dans le conteneur
COPY . /var/www/html/

# Corriger les permissions
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Config Apache : pointer vers le bon dossier, autoriser l'accès, et définir le fichier par défaut
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