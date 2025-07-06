FROM php:8.2-apache

RUN a2enmod rewrite

COPY ./docker/apache/cardikit.conf /etc/apache2/sites-available/cardikit.conf

RUN a2ensite cardikit.conf && a2dissite 000-default.conf

COPY . /var/www/html/

RUN docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer install --no-interaction --no-dev --optimize-autoloader --working-dir=/var/www/html

