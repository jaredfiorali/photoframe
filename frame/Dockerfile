FROM php:apache

RUN mkdir -p /var/www/html
RUN mkdir -p /etc/apache2/sites-enabled
WORKDIR /var/www/html

# RUN apk --no-cache add uglify-js php7 php7-pdo_mysql php7-gd apache2 php7-apache2 php7-phalcon composer

RUN curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | sudo bash
RUN apt update && apt install -y node-uglify composer php7-phalcon
RUN apt clean autoclean && apt autoremove --yes && rm -rf /var/lib/{apt,dpkg,cache,log}/
RUN docker-php-ext-configure gd
RUN docker-php-ext-configure pdo_mysql

ADD composer.json .
RUN composer install --prefer-dist --no-scripts --no-dev && rm -rf /root/.composer

ADD . .

EXPOSE 80

# By default, simply start apache.
RUN ["mv", "-f", "/var/www/html/photoframe.conf", "/etc/apache2/sites-enabled"]
RUN ["mv", "-f", "/var/www/html/httpd.conf", "/etc/apache2/httpd.conf"]
RUN ["chmod", "-R", "777", "."]
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]