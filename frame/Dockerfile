FROM php:apache:latest

RUN mkdir -p /var/www/html
RUN mkdir -p /etc/apache2/sites-enabled
WORKDIR /var/www/html

# RUN apk --no-cache add uglify-js php7 php7-pdo_mysql php7-gd apache2 php7-apache2 php7-phalcon composer
RUN apk --no-cache add uglify-js php7-pdo_mysql php7-gd php7-phalcon composer

ADD composer.json .
RUN composer install --prefer-dist --no-scripts --no-dev && rm -rf /root/.composer

ADD . .

EXPOSE 80

# By default, simply start apache.
RUN ["mv", "-f", "/var/www/html/photoframe.conf", "/etc/apache2/sites-enabled"]
RUN ["mv", "-f", "/var/www/html/httpd.conf", "/etc/apache2/httpd.conf"]
RUN ["chmod", "-R", "777", "."]
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]