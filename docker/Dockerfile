FROM alpine:latest

RUN mkdir -p /var/www/html
WORKDIR /var/ww/html

RUN apk update
RUN apk add apache2
RUN apk add php7-phalcon
RUN apk add composer

RUN wget https://github.com/jaredfiorali/photoframe/archive/master.zip
RUN unzip master -d /var/www/html
RUN rm master.zip

RUN ls -a
# RUN composer install

# RUN /usr/sbin/httpd -D FOREGROUND -f /web/config/httpd.conf