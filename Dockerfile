FROM php:8.4
WORKDIR /workdir
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer
ENV PATH="$PATH:/opt/composer/vendor/bin"
RUN apt-get update && apt-get install -y zip unzip git curl
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

RUN docker-php-ext-install pdo_mysql

COPY . .
WORKDIR /workdir/laravel_app
RUN composer install
CMD sh -c "npm install && npm run build && php artisan serve --host 0.0.0.0"
EXPOSE 8000
