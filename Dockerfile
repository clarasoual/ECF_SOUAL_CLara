FROM php:8.2-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y nginx \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

RUN echo 'server {\
    listen 80;\
    root /var/www/html/PROJET/UTILISATEUR;\
    index USR-index.php;\
    location / {\
        try_files $uri $uri/ /USR-index.php?$query_string;\
    }\
    location ~ \.php$ {\
        fastcgi_pass 127.0.0.1:9000;\
        fastcgi_index USR-index.php;\
        include fastcgi_params;\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\
    }\
}' > /etc/nginx/sites-available/default

CMD service nginx start && php-fpm

EXPOSE 80