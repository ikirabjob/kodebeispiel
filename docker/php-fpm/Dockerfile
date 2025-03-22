ARG PHP_VERSION
FROM php:${PHP_VERSION}
ARG USERID
ARG USERNAME=www-data

COPY docker/php-fpm/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini
RUN apt update && apt install -y libicu-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install intl
RUN apt-get update && apt-get install -y --no-install-recommends git curl zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    libc-client-dev \
    libkrb5-dev \
    nano \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

# Install extensions
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis
RUN docker-php-ext-install pdo_mysql pdo_pgsql zip exif

RUN docker-php-ext-configure imap \
    --with-kerberos \
    --with-imap-ssl
RUN docker-php-ext-configure gd --enable-gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd imap && \
    docker-php-source delete

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/* tmp/* /var/cache/*

RUN usermod -u ${USERID} ${USERNAME} && \
    groupmod -g ${USERID} ${USERNAME}

COPY --chown=${USERID}:${USERID} ./ /app
WORKDIR /app

RUN mkdir "/docker-scripts"
RUN mv ./docker/php-fpm/Entrypoint.sh /docker-scripts/Entrypoint.sh && \
    mv ./docker/wait-for-it.sh /docker-scripts/wait-for-it.sh
RUN chmod +x /docker-scripts/Entrypoint.sh /docker-scripts/wait-for-it.sh
USER ${USERNAME}
ENTRYPOINT ["/docker-scripts/Entrypoint.sh"]
