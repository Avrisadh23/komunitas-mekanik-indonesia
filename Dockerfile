FROM php:8.2-fpm

# 1. Install dependencies sistem dan Nginx
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nginx \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install PHP extensions (termasuk pdo_pgsql untuk PostgreSQL)
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# 3. Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set working directory
WORKDIR /var/www

# 5. Copy file project
COPY . /var/www

# 6. Install dependencies Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 7. Setup permission folder storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 8. Copy konfigurasi Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# 8b. Konfigurasi PHP-FPM pakai Unix socket (hindari bentrok port sama Nginx)
RUN sed -i 's/^listen = .*/listen = \/run\/php-fpm.sock/' /usr/local/etc/php-fpm.d/www.conf \
    && echo "listen.owner = www-data" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "listen.group = www-data" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "listen.mode = 0660" >> /usr/local/etc/php-fpm.d/www.conf

# 9. Expose port (Cloud Run default 8080)
EXPOSE 8080

# 10. Script untuk menjalankan Nginx & PHP-FPM (Support Dynamic PORT for Render)
RUN echo "#!/bin/sh\n\
: \"\${PORT:=8080}\"\n\
sed -i \"s/8080/\$PORT/g\" /etc/nginx/sites-available/default\n\
php artisan migrate --force\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
service nginx start\n\
php-fpm" > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]