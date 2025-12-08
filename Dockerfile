FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite

# Install system deps + GD + ZIP + PDO
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
 && docker-php-ext-configure zip \
 && docker-php-ext-install zip pdo pdo_mysql \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd \
 && rm -rf /var/lib/apt/lists/*

# Apache config
COPY ./docker/apache/cardikit.conf /etc/apache2/sites-available/cardikit.conf
RUN a2ensite cardikit.conf && a2dissite 000-default.conf

# App code
COPY . /var/www/html/

# Give Apache permission to write to storage directories
RUN mkdir -p /var/www/html/public/qrcodes \
    && mkdir -p /var/www/html/images \
    && mkdir -p /var/www/html/uploads \
    && mkdir -p /var/www/html/uploads/blog \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/public/qrcodes \
    && chmod -R 775 /var/www/html/images \
    && chmod -R 775 /var/www/html/uploads

# Ensure runtime permissions if the host bind mount overrides ownership
RUN usermod -a -G www-data root

# Runtime entrypoint to fix permissions each start (handles bind mounts)
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer install --no-interaction --no-dev --optimize-autoloader --working-dir=/var/www/html
