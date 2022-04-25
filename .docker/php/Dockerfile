FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libwebp-dev \
    libonig-dev \
    libpq-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_pgsql pgsql mbstring zip exif pcntl gd \
    && docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -fsSL https://deb.nodesource.com/setup_current.x | bash - \
    && apt-get install -y nodejs

RUN groupadd -g 1000 admin && useradd -u 1000 -ms /bin/bash -g admin admin
COPY --chown=admin:admin . /var/www
USER admin

EXPOSE 9000
CMD ["php-fpm"]
