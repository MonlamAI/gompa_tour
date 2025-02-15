# Use an official PHP runtime as a parent image
FROM php:8.1-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy your PHP application code into the container
COPY . .

# Install PHP extensions and other dependencies
RUN apt-get update && \
    apt-get install -y libpng-dev && \
    docker-php-ext-install pdo pdo_mysql gd && \
    apt-get install -y zip unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY php.ini /usr/local/etc/php/php.ini-development
COPY php.ini /usr/local/etc/php/php.ini-production

# Run composer install
RUN composer clear-cache
# Expose the port Apache listens on
EXPOSE 80

# Start Apache when the container runs
CMD ["bash", "-c", "apache2-foreground && service apache2 restart"]
