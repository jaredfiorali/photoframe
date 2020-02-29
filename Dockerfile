FROM alpine:latest

RUN mkdir -p /var/www/html
RUN mkdir -p /etc/apache2/sites-enabled
WORKDIR /var/www/html

RUN apk --no-cache update
RUN apk --no-cache add php7 php7-pdo_mysql
RUN apk --no-cache add apache2 php7-apache2
RUN apk --no-cache add php7-phalcon
RUN apk --no-cache add composer

ADD app ./app
ADD includes ./includes
ADD public ./public
ADD .htaccess .
ADD composer.json .
ADD docker/apache2/photoframe.conf .
ADD docker/apache2/httpd.conf .

RUN composer install --no-dev

EXPOSE 80

# By default, simply start apache.
RUN ["mv", "-f", "/var/www/html/photoframe.conf", "/etc/apache2/sites-enabled"]
RUN ["mv", "-f", "/var/www/html/httpd.conf", "/etc/apache2/httpd.conf"]
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]