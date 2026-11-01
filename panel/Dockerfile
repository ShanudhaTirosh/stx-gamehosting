# syntax=docker.io/docker/dockerfile:1.13-labs
# STX Enterprises - Full Multi-Stage Source Production Dockerfile
# Builds everything from source: PHP 8.5 extensions, Composer dependencies, Yarn Vite frontend assets, and all pre-installed plugins.

# ================================
# Stage 1: Base PHP 8.5 Image with Extensions
# ================================
FROM --platform=$TARGETOS/$TARGETARCH php:8.5-fpm-alpine AS base

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions bcmath gd intl zip opcache pcntl pdo_mysql pdo_pgsql bz2

RUN rm /usr/local/bin/install-php-extensions

# Use production php.ini as baseline
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# ================================
# Stage 2: Composer Dependencies
# ================================
FROM --platform=$TARGETOS/$TARGETARCH base AS composer

WORKDIR /build

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy composer definitions
COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --no-autoloader --no-scripts

# ================================
# Stage 3: Yarn Frontend Dependencies
# ================================
FROM --platform=$TARGETOS/$TARGETARCH node:22-alpine AS yarn

WORKDIR /build

COPY package.json yarn.lock ./

RUN yarn config set network-timeout 300000 \
    && yarn install --frozen-lockfile

# ================================
# Stage 4: Composer Autoload & Plugins Integration
# ================================
FROM --platform=$TARGETOS/$TARGETARCH composer AS composerbuild

# Copy entire source code (including panel plugins, custom controllers, providers)
COPY --exclude=docker/ . ./

RUN composer dump-autoload --optimize

# Pre-resolve composer dependencies for all plugins
RUN php artisan p:plugin:composer

# ================================
# Stage 5: Build Frontend Assets (Vite)
# ================================
FROM --platform=$TARGETOS/$TARGETARCH yarn AS yarnbuild

WORKDIR /build

# Copy source code and vendor autoload
COPY --exclude=docker/ . ./
COPY --from=composer /build .

RUN yarn run build

# ================================
# Stage 6: Final Production Application Image
# ================================
FROM --platform=$TARGETOS/$TARGETARCH base AS final

LABEL maintainer="STX Enterprises Lanka (Pvt) Ltd"
LABEL description="STX Enterprises - Commercial Cloud & Game Server Control Plane"

WORKDIR /var/www/html

# Install required system packages
RUN apk add --no-cache \
    caddy ca-certificates supervisor supercronic fcgi coreutils \
    zip unzip 7zip bzip2-dev yarn git

# Copy composer binary for runtime maintenance
COPY --from=composer /usr/local/bin/composer /usr/local/bin/composer

# Copy built application source code & compiled Vite public assets
COPY --chown=root:www-data --chmod=770 --from=composerbuild /build .
COPY --chown=root:www-data --chmod=770 --from=yarnbuild /build/public ./public

# Setup Pelican volume directory structure and symlinks
RUN mkdir -p /pelican-data/storage /var/run/supervisord \
    && ln -s /pelican-data/.env /var/www/html/.env \
    && ln -s /pelican-data/database/database.sqlite ./database/database.sqlite \
    && ln -s /pelican-data/storage /var/www/html/public/storage \
    && ln -s /pelican-data/storage /var/www/html/storage/app/public \
    && chown -R www-data:www-data /pelican-data ./storage ./bootstrap/cache /var/run/supervisord /var/www/html/public/storage /var/www/html/plugins \
    && chmod -R 775 /pelican-data ./storage ./bootstrap/cache /var/run/supervisord /var/www/html/plugins \
    && chown -R www-data:www-data /usr/local/etc/php/ /usr/local/etc/php-fpm.d/ /var/www/html/composer.json /var/www/html/composer.lock

# Copy server & daemon configurations
COPY docker/php/pelican.ini /usr/local/etc/php/conf.d/zz-pelican.ini
COPY docker/php/pelican-pool.conf /usr/local/etc/php-fpm.d/zz-pelican.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/Caddyfile /etc/caddy/Caddyfile
COPY docker/crontab /etc/crontabs/crontab

COPY docker/entrypoint.sh /entrypoint.sh
COPY docker/healthcheck.sh /healthcheck.sh
RUN chmod +x /entrypoint.sh /healthcheck.sh

HEALTHCHECK --interval=30s --timeout=10s --start-period=2m --retries=3 \
  CMD /bin/ash /healthcheck.sh

EXPOSE 80 443

VOLUME /pelican-data

USER www-data

ENTRYPOINT [ "/bin/ash", "/entrypoint.sh" ]
CMD [ "supervisord", "-n", "-c", "/etc/supervisord.conf" ]
