FROM php:apache

RUN mkdir -p /var/www/html
WORKDIR /var/www/html

ADD . .

CMD ["apache2-foreground"]
