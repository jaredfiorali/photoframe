FROM alpine:latest

RUN mkdir -p /var/www/html
WORKDIR /var/www/html

RUN apk --no-cache add php7 php7-json php7-openssl php7-simplexml

ADD . .

CMD ["php", "pull.php"]
