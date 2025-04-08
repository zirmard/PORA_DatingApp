FROM php:8.0-apache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli && docker-php-ext-install pdo pdo_mysql

RUN apt-get update
RUN apt-get install -y \
        libzip-dev \
        zip \
        libpng-dev \
  && docker-php-ext-install zip

RUN docker-php-ext-install gd

RUN apt-get install -y nano

WORKDIR /var/www/html/laravel_basecode

#COPY *.json .

COPY . .

COPY .env.example .env

RUN composer install

RUN php artisan config:cache
RUN php artisan cache:clear
RUN php artisan key:generate
RUN php artisan route:clear
#RUN php artisan migrate
#RUN php artisan db:seed

#RUN echo "define('L5_SWAGGER_CONST_HOST', env('L5_SWAGGER_CONST_HOST'));" >> config/constant.php

# needs to check error with
RUN php artisan l5-swagger:generate   

ENV WAIT_VERSION 2.7.2
ADD https://github.com/ufoscout/docker-compose-wait/releases/download/$WAIT_VERSION/wait /wait
RUN chmod +x /wait

RUN chmod -R guo+w storage

EXPOSE 80

RUN a2enmod rewrite  

