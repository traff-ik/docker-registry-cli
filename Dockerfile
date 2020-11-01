FROM php:7.4-zts-alpine

LABEL maintainer="Oleg Tikhonov <to@toro.one>"

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN apk add --no-cache \
    git \
    unzip \
    zip

COPY ./docker/php/conf.d "${PHP_INI_DIR}"/conf.d
COPY ./docker/php/php.ini "${PHP_INI_DIR}"/php.ini

RUN install-php-extensions \
    bcmath \
    gmp \
    parallel \
    xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

WORKDIR /usr/lib/registry

COPY composer.json composer.lock ./
RUN composer check-platform-reqs \
    && composer install \
    && composer clear-cache

COPY bin/ ./bin
COPY src/ ./src
RUN chmod -R +x /usr/lib/registry/bin

ENTRYPOINT [ "/usr/lib/registry/bin/registry" ]
