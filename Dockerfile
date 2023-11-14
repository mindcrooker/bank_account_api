FROM php:8.2-fpm-alpine


RUN apk --no-cache add \
        bash \
        git \
        unzip \
        icu-dev \
        libzip-dev \
    && docker-php-ext-install pdo_mysql intl zip
#RUN apt update \
#    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
#    && docker-php-ext-install intl opcache pdo pdo_mysql \
#    && pecl install apcu \
#    && docker-php-ext-enable apcu \
#    && docker-php-ext-configure zip \
#    && docker-php-ext-install zip

WORKDIR /var/www/bank_account_api

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

#RUN git config --global user.email "olaf.drozdowski@gmail.com" \ 
#    && git config --global user.name "Olaf Drozdowski"