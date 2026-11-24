FROM php:8.5.12-fpm-bookworm@sha256:eec275cd99580e0a6e103dd343a7c160c86917003f6b9bba2d4c75d82419c683

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        default-mysql-client \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        pcntl \
        pdo_mysql \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.11.2@sha256:cd3a90dc5624c59a2f9ddf80dad08569f06416db31de519024f249d63bca554b /usr/bin/composer /usr/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/books.ini

WORKDIR /var/www/html
