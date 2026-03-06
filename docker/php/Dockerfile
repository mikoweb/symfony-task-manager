FROM dunglas/frankenphp:latest

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    vim \
    nano \
    mcedit \
    locales coreutils apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev

RUN install-php-extensions \
    pdo_mysql \
    iconv \
    intl \
    opcache \
    mbstring \
    xsl \
    ctype \
    xml

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/Caddyfile /etc/caddy/Caddyfile
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENV COMPOSER_ALLOW_SUPERUSER=1

ENTRYPOINT ["entrypoint.sh"]

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
