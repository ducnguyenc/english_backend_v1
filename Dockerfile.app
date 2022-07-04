FROM --platform=linux/amd64 php:8.1.0-fpm

RUN apt-get update -y
RUN apt-get install -y
RUN apt-get install -y \
    libzip-dev unzip libonig-dev libpng-dev \
    libwebp-dev libjpeg62-turbo-dev libxpm-dev \
    libfreetype6-dev libpq-dev
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring 

# config and install php gd
RUN docker-php-ext-configure gd \
    --with-jpeg \
    --with-freetype
RUN docker-php-ext-install gd

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install nodejs
RUN apt-get install npm -y \
    && npm cache clean -f \
    && npm install -g n \
    && n 12.18.4 \
    && mkdir /.npm \
    && chown $UID:$GID /.npm -R

COPY ./ /var/www/html
RUN composer install
RUN cp .env.example .env
RUN chmod -R 777 storage
# CMD php artisan serve --host=0.0.0.0 --port=$PORT