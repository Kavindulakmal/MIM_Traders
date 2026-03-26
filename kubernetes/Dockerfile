# Dockerfile — place this in the root of the MIM_Traders repository
# Build: docker build -t your-dockerhub-username/mim-traders:latest .
# Push:  docker push your-dockerhub-username/mim-traders:latest

FROM php:8.2-apache

# Install required PHP extensions for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application source into web root
COPY . /var/www/html/

# Fix permissions for uploaded images directory
RUN mkdir -p /var/www/html/uploaded_img \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/uploaded_img

# Set working directory
WORKDIR /var/www/html

EXPOSE 80
