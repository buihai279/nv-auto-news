# Stage 1: Build stage để cài đặt các gói dependencies của PHP và Composer
FROM php:8.2-fpm AS builder
# Sử dụng PHP phiên bản 8.1 (hoặc tùy theo yêu cầu của dự án)

# Cài đặt các extension cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Cài đặt Composer

# Thiết lập thư mục làm việc cho Laravel
WORKDIR /var/www

# Sao chép mã nguồn Laravel vào container
COPY . .

COPY composer.json composer.lock /var/www/
# Cấp quyền cho thư mục storage và bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN php composer.phar  install
# Cài đặt các package của Laravel
RUN ls -llath
# Expose port 8000 để truy cập ứng dụng
EXPOSE 8000

# Command để chạy Laravel bằng PHP built-in server
CMD php artisan serve --host=0.0.0.0 --port=8000
