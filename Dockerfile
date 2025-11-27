FROM php:8.2-apache

# Cài extension MySQLi
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy toàn bộ source vào thư mục web
COPY . /var/www/html/

# Set quyền
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
