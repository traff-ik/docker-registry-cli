FROM php:8.0-cli-alpine

LABEL maintainer="Oleg Tikhonov <to@toro.one>"
LABEL name="php-registry-cli"

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN apk add --no-cache \
    git \
    zip

# PHP configuration

COPY ./docker/php/php.ini "${PHP_INI_DIR}"/php.ini
COPY ./docker/php/conf.d.dev "${PHP_INI_DIR}"/conf.d

# PHP extensions

RUN install-php-extensions \
    xdebug

# Composer

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --2 --install-dir=/usr/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Working directory

WORKDIR /usr/lib/registry

COPY composer.json composer.lock ./

RUN composer check-platform-reqs \
    && composer -V

# Project files

COPY bin/ ./bin
COPY src/ ./src
RUN chmod -R +x /usr/lib/registry/bin

ENTRYPOINT ["/usr/lib/registry/bin/cli"]
