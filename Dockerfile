#syntax=docker/dockerfile:1.4

# The different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target

# Builder images
FROM composer/composer:2-bin AS composer

FROM mlocati/php-extension-installer:latest AS php_extension_installer

# Build Caddy with the Mercure and Vulcain modules
FROM caddy:2.6-builder-alpine AS app_caddy_builder

RUN xcaddy build \
	--with github.com/dunglas/mercure \
	--with github.com/dunglas/mercure/caddy \
	--with github.com/dunglas/vulcain \
	--with github.com/dunglas/vulcain/caddy

# Prod image
FROM php:8.2-fpm-alpine AS app_php

# Allow to use development versions of Symfony
ARG STABILITY="stable"
ENV STABILITY ${STABILITY}

# Allow to select Symfony version
ARG SYMFONY_VERSION=""
ENV SYMFONY_VERSION ${SYMFONY_VERSION}

ENV APP_ENV=prod

WORKDIR /srv/app

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=php_extension_installer --link /usr/bin/install-php-extensions /usr/local/bin/

# persistent / runtime deps
RUN apk update && apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
		nodejs \
		npm \
	;

RUN set -eux; \
    install-php-extensions \
        intl \
        zip \
        apcu \
        opcache \
        pdo_mysql \
        mysqli \
    ;

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --link docker/php/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY --link docker/php/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/

COPY --link docker/php/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
RUN mkdir -p /var/run/php

COPY --link docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

COPY --link docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer --link /composer /usr/bin/composer

# prevent the reinstallation of vendors at every changes in the source code
COPY --link composer.* symfony.* ./
RUN set -eux; \
    if [ -f composer.json ]; then
