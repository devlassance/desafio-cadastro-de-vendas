# PHP-FPM with Composer and common extensions for Laravel
FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    git curl bash icu-dev libzip-dev oniguruma-dev libpng-dev libjpeg-turbo-dev freetype-dev \
    mysql-client \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_mysql mbstring zip intl gd bcmath opcache

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/tmp/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Redis extension (requires phpize build deps)
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    pecl install redis; \
    docker-php-ext-enable redis; \
    apk del -f .build-deps

WORKDIR /var/www/html

# Make php-fpm listen on all interfaces (for Nginx in a separate container)
RUN set -eux; \
        sed -ri 's~^;?listen\s*=\s*.*$~listen = 0.0.0.0:9000~' /usr/local/etc/php-fpm.d/www.conf; \
        sed -ri 's~^;?clear_env\s*=\s*.*$~clear_env = no~' /usr/local/etc/php-fpm.d/www.conf || true; \
        if [ -f /usr/local/etc/php-fpm.d/zz-docker.conf ]; then \
            sed -ri 's~^listen\s*=\s*.*$~listen = 0.0.0.0:9000~' /usr/local/etc/php-fpm.d/zz-docker.conf; \
        fi

EXPOSE 9000

# Copy startup script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
