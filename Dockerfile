# Stage 1: Build stage để cài đặt các gói dependencies của PHP và Composer
FROM php:8.2-fpm-alpine as builder

# Cài đặt các dependency hệ thống và PHP extensions cần thiết
RUN apk add --no-cache \
    curl \
    bash \
#    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install exif pcntl

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy file composer trước để tận dụng caching (nếu composer.json không thay đổi, sẽ không cần cài đặt lại)
COPY composer.json composer.lock composer.phar  ./

# Cài đặt các gói Composer mà không cần các dev dependencies
RUN php composer.phar install --no-dev --prefer-dist --optimize-autoloader --no-scripts --no-progress --no-interaction

# Stage 2: Production stage
FROM php:8.2-fpm-alpine

# Copy các PHP extensions đã cài đặt từ builder stage
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions

# Copy thư mục làm việc từ builder stage
COPY --from=builder /var/www/html /var/www/html
COPY . /var/www/html
RUN ls -llath /var/www/html
# Thiết lập quyền cho thư mục storage và bootstrap/cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Mở cổng 9000 cho PHP-FPM
EXPOSE 9000

# Lệnh mặc định khởi chạy PHP-FPM
CMD ["php-fpm"]
