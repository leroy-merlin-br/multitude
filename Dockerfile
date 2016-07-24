FROM php:7.0-apache

# Install modules
RUN apt-get update && apt-get install -y \
        git \
        libssl-dev \
    && docker-php-ext-install mbstring tokenizer pcntl

# Install pecl extensions
RUN pecl install mongodb \
    && pecl install xdebug
RUN docker-php-ext-enable mongodb xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer \
        | php -- --install-dir=/usr/local/bin \
        && mv /usr/local/bin/composer.phar /usr/local/bin/composer

# Configure apache
RUN sed -i "s/DocumentRoot .*/DocumentRoot \/var\/www\/html\/public/" /etc/apache2/apache2.conf
RUN echo "error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT" >> /usr/local/etc/php/conf.d/error.ini
RUN echo "log_errors = On" >> /usr/local/etc/php/conf.d/error.ini
RUN a2enmod rewrite

# Set storage to writable
COPY . /var/www/html/
RUN chmod -R 777 storage/
