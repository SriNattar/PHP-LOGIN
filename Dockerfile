FROM php:8.2-apache

# Install PostgreSQL PDO driver and other extensions
RUN apt-get update && apt-get install -y libpq-dev && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql && docker-php-ext-enable pdo pdo_pgsql

# Install mysqli extension (optional, for backward compatibility)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite if needed (optional for this simple app but good practice)
RUN a2enmod rewrite

# Copy source code
COPY src/ /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html/
