FROM alpine:latest

# Set our default working directory to the standard apache location
WORKDIR /var/www/html

# Take the incoming GIT_COMMIT argument, and save it to a file
ARG GIT_COMMIT=unspecified
LABEL git_commit=$GIT_COMMIT
RUN echo $GIT_COMMIT > ./git_revision

# Create our default directories for our custom apache files
RUN mkdir -p /var/www/html
RUN mkdir -p /etc/apache2/sites-enabled

# Install dependencies
RUN apk --no-cache add uglify-js php7 php7-pdo_mysql php7-gd apache2 php7-apache2 php7-phalcon composer

# Run composer to grab the remaining PHP packages
ADD composer.json .
RUN composer install --prefer-dist --no-scripts --no-dev && rm -rf /root/.composer

# Transfer our repo files to our working direcory
ADD . .

# Open up port 80 for apache traffic
EXPOSE 80

# Copy our custom apache files to apache's directory, and run apache
RUN ["mv", "-f", "/var/www/html/photoframe.conf", "/etc/apache2/sites-enabled"]
RUN ["mv", "-f", "/var/www/html/httpd.conf", "/etc/apache2/httpd.conf"]
RUN ["chmod", "-R", "777", "."]
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]
