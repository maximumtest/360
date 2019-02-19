FROM php:7.3.1-fpm-alpine

COPY .docker/php/config/php.ini /usr/local/etc/php/

WORKDIR /var/www/

# Set timezone
RUN apk add -U tzdata
RUN cp /usr/share/zoneinfo/Europe/Moscow /etc/localtime

# PHP configuration
RUN buildDeps="git libzip-dev unzip postgresql-dev autoconf g++ make" && \
    apk update && \
    apk add $buildDeps

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Install mongodb php ext
RUN apk --update upgrade \
    && apk add autoconf g++ make \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install nginx

RUN apk add nginx

COPY .docker/nginx/config/nginx.conf /etc/nginx/nginx.conf
COPY .docker/nginx/config/production.conf /etc/nginx/conf.d/default.conf

# Install NodeJS

RUN apk add --no-cache nodejs nodejs-npm

# Install supervisord

RUN apk add --no-cache --update supervisor

COPY .docker/supervisord/production.conf /etc/supervisord.conf

# Setup application

COPY .env.dist .env
COPY . ./
RUN chmod 777 -R bootstrap/ storage/

RUN composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader
RUN npm install
RUN npm run build

# remove not necessary files
RUN apk del --purge autoconf g++ make

EXPOSE 80

# Setup entrypoint

COPY .docker/docker-entrypoint.sh /usr/local/bin/

CMD ["docker-entrypoint.sh"]
