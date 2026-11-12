FROM composer:2 AS composer

# Git 2.56.1 fixes the November 2026 Git security advisories. Debian's older
# packages are still affected; build the verified upstream release on the same
# base as the runtime so its HTTPS helper uses compatible shared libraries.
FROM php:8.5.12-fpm AS git-build
RUN apt-get update \
    && apt-get install --no-install-recommends --yes \
        build-essential ca-certificates curl libcurl4-openssl-dev libexpat1-dev \
        libpcre2-dev libssl-dev perl xz-utils zlib1g-dev \
    && curl --fail --silent --show-error --location \
        https://www.kernel.org/pub/software/scm/git/git-2.56.1.tar.xz --output /tmp/git.tar.xz \
    && echo 'f0b0bf89264b9bd2e1c640363e7529643989da76281600473c50745cd34a607f  /tmp/git.tar.xz' | sha256sum --check --strict \
    && tar --extract --xz --file /tmp/git.tar.xz --directory /tmp \
    && make -C /tmp/git-2.56.1 -j2 prefix=/usr/local \
        NO_RUST=YesPlease NO_GETTEXT=YesPlease NO_TCLTK=YesPlease USE_LIBPCRE2=YesPlease all \
    && make -C /tmp/git-2.56.1 prefix=/usr/local \
        NO_RUST=YesPlease NO_GETTEXT=YesPlease NO_TCLTK=YesPlease USE_LIBPCRE2=YesPlease \
        DESTDIR=/opt/git install

FROM php:8.5.12-fpm

COPY --from=git-build /opt/git/usr/local/ /usr/local/

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install --yes --no-install-recommends $PHPIZE_DEPS ca-certificates curl libexpat1 libpcre2-8-0 openssh-client perl zlib1g unzip python3 \
    && pecl install pcov-1.0.12 \
    && docker-php-ext-enable pcov \
    && apt-get purge --yes --auto-remove $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

ARG APP_UID=1000
ARG APP_GID=1000

RUN groupadd --non-unique --gid "$APP_GID" systemuser \
    && useradd --non-unique --uid "$APP_UID" --gid "$APP_GID" --home-dir /app --no-create-home --shell /usr/sbin/nologin systemuser

RUN rm /usr/local/etc/php-fpm.d/www.conf

COPY etc/docker/fpm/zz-project-yii.conf /usr/local/etc/php-fpm.d/zz-project-yii.conf
COPY --chmod=0644 etc/docker/fpm/zz-project-yii.ini /usr/local/etc/php/conf.d/zz-project-yii.ini
COPY etc/docker/fpm/entrypoint.sh /usr/local/bin/project-yii-fpm

RUN chmod 0755 /usr/local/bin/project-yii-fpm \
    && mkdir -p /usr/local/var/run \
    && chown systemuser:systemuser /usr/local/var/run

ENV COMPOSER_CACHE_DIR=/app/var/cache/composer

WORKDIR /app

# Preserve the distro executable path without retaining its affected Git package.
# A package that reintroduces /usr/bin/git makes this layer fail closed.
RUN ln -s /usr/local/bin/git /usr/bin/git \
    && test "$(git --version)" = 'git version 2.56.1' \
    && test "$(/usr/bin/git --version)" = 'git version 2.56.1' \
    && test "$(readlink /usr/bin/git)" = /usr/local/bin/git \
    && test -x "$(git --exec-path)/git-remote-https"

USER systemuser:systemuser

CMD ["project-yii-fpm"]
