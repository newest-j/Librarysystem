# Use the official PHP image with Apache
FROM php:8.2-apache

# Copy all files to the Apache server root
COPY . /var/www/html/

# Expose port 80 to the internet
EXPOSE 80