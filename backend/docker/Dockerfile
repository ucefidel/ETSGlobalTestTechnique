FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y git curl zip unzip libssl-dev pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Install PHP MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Point Apache DocumentRoot to public/ folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/symfony.conf \
    && a2enconf symfony

# Copy source code
COPY .. .

# Install dependencies (vendor/ is gitignored, cette étape le recrée)
RUN if [ -f "composer.json" ]; then composer install --no-interaction --optimize-autoloader; fi

EXPOSE 80

CMD ["apache2-foreground"]
