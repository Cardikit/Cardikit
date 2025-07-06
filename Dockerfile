FROM php:8.2-apache
RUN a2enmod rewrite
# Set the document root to the public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update Apache config to use new doc root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer install --no-interaction --no-dev --optimize-autoloader --working-dir=/var/www/html

