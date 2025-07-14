# Use the official PHP image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /opt/render/project

# Copy all application files
COPY . /opt/render/project/

# Set proper permissions
RUN chown -R www-data:www-data /opt/render/project && \
    chmod -R 755 /opt/render/project

# Enable Apache mod_rewrite (if needed)
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]