FROM php:apache

# Create a directory where we will store the site definition for apache
RUN mkdir -p /etc/apache2/sites-enabled

# Install applicable packages
RUN apt update && apt install -y node-uglify
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | bash

# Install PHP modules
RUN docker-php-ext-configure gd
RUN docker-php-ext-configure pdo_mysql

# Have composer install PHP extensions
ADD composer.json .
RUN composer install --prefer-dist --no-scripts --no-dev && rm -rf /root/.composer

# Remove apt cache from system
RUN apt clean autoclean && apt autoremove --yes && rm -rf /var/lib/{apt,dpkg,cache,log}/

# Add our repo files to the image
ADD . .

# Expose port 80 to the world
EXPOSE 80

# By default, simply start apache.
RUN ["mv", "-f", "/var/www/html/photoframe.conf", "/etc/apache2/sites-enabled"]
RUN ["mv", "-f", "/var/www/html/httpd.conf", "/etc/apache2/httpd.conf"]
RUN ["chmod", "-R", "777", "."]
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]