FROM composer:2 AS composer

FROM php:8.5-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install --yes --no-install-recommends $PHPIZE_DEPS git openssh-client unzip \
    && pecl install pcov-1.0.12 \
    && docker-php-ext-enable pcov \
    && apt-get purge --yes --auto-remove $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

RUN groupadd --gid 1000 systemuser \
    && useradd --uid 1000 --gid 1000 --home-dir /app --no-create-home --shell /usr/sbin/nologin systemuser

RUN rm /usr/local/etc/php-fpm.d/www.conf

COPY etc/docker/fpm/zz-project-yii.conf /usr/local/etc/php-fpm.d/zz-project-yii.conf
COPY etc/docker/fpm/entrypoint.sh /usr/local/bin/project-yii-fpm

RUN chmod 0755 /usr/local/bin/project-yii-fpm \
    && mkdir -p /usr/local/var/run \
    && chown systemuser:systemuser /usr/local/var/run

WORKDIR /app

USER systemuser:systemuser

CMD ["project-yii-fpm"]
