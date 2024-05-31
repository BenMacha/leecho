FROM php:8.3-fpm-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY ./ /var/www/html/

RUN apk add --no-cache bash
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' |  bash
RUN apk add symfony-cli

RUN apk --no-cache add nodejs yarn --repository=http://dl-cdn.alpinelinux.org/alpine/edge/community
