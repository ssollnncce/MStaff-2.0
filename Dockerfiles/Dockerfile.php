FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
      libzip-dev \
      zip \
      unzip \
      && docker-php-ext-install pdo_mysql zip \
      && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/backend

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-scripts --no-autoloader

COPY . .

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data storage bootstrap/cache