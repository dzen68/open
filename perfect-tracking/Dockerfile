FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Update package lists and install required dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install and enable PostgreSQL PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copy application files to the Apache document root
COPY . /var/www/html/

# Expose port (Render injects PORT, but Apache binds to 80 by default)
EXPOSE 80

# The default command for php:apache starts Apache in the foreground
# But Render requires binding to $PORT, so we need to intercept the ports.conf
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
