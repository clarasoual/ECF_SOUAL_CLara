FROM php:8.2-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y nginx \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/
COPY nginx.conf /etc/nginx/sites-available/default
COPY start.sh /start.sh

RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/ \
    && chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]