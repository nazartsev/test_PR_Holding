FROM php:8.0-fpm-alpine
RUN mkdir /app
WORKDIR /app
RUN apk upgrade --update && apk add --no-cache \
        $PHPIZE_DEPS \
        git \
        wget \
        vim \
        tzdata \
        supervisor \
        pkgconfig \
        icu-libs \
        imagemagick \
        icu \
        libpng \
        freetype \
        libjpeg-turbo \
        openssl \
        unixodbc \
        zlib \
        libzip-dev \
        rabbitmq-c \
        imagemagick \
        libwebp
RUN cp /usr/share/zoneinfo/Europe/Moscow /etc/localtime
RUN apk --no-cache add php8-iconv
RUN apk --no-cache add php8-mbstring
RUN apk --no-cache add php8-zip
RUN apk --no-cache add php8-ctype
RUN apk --no-cache add php8-opcache
RUN apk --no-cache add php8-pcntl
RUN apk --no-cache add php8-bcmath
RUN apk --no-cache add php8-pecl-apcu
RUN apk --no-cache add php8-gd
RUN apk --no-cache add php8-pecl-amqp
RUN apk --no-cache add php8-pecl-igbinary
RUN apk --no-cache add php8-pecl-imagick
RUN apk --no-cache add php8-pdo
RUN apk --no-cache add php8-pdo_mysql
RUN apk --no-cache add php8-sockets
RUN apk --no-cache add php8-tokenizer
RUN apk --no-cache add php8-dom
RUN apk --no-cache add php8-sodium
RUN apk --no-cache add php8-fileinfo
RUN apk --no-cache add php8-simplexml
RUN apk --no-cache add php8-xml
RUN apk --no-cache add php8-xmlwriter
RUN apk --no-cache add php8-xmlreader
RUN docker-php-ext-install \
        pdo \
        pdo_mysql \
        sockets \
        zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Moscow /etc/localtime
RUN "date"


RUN apk add ruby ruby-full ruby-dev && \
    gem install compass

WORKDIR /app

RUN apk add icu-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-enable intl