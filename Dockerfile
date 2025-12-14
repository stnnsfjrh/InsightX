FROM php:8.2-apache

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Permission
RUN chown -R www-data:www-data /var/www/html

# Fly.io pakai port 8080
EXPOSE 8080

# Ganti port Apache
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf \
 && sed -i 's/:80/:8080/g' /etc/apache2/sites-enabled/000-default.conf

CMD ["apache2-foreground"]
