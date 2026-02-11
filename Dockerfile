FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite if needed (optional for this simple app but good practice)
RUN a2enmod rewrite

# Copy source code
COPY src/ /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html/
