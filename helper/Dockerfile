FROM alpine:latest

RUN mkdir -p /var/www/html
RUN mkdir -p /etc/apache2/sites-enabled
WORKDIR /var/www/html

RUN apk --no-cache add uglify-js php7 php7-pdo_mysql apache2 php7-apache2 php7-phalcon composer

ADD composer.json .
RUN composer install --prefer-dist --no-scripts --no-dev && rm -rf /root/.composer

ADD . .

EXPOSE 8080

# By default, simply start apache.
ENV PORT 80
ENTRYPOINT []
CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground
RUN ["chmod", "-R", "777", "."]
# CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]