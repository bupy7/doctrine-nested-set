FROM alpine:3.7

RUN apk add --no-cache php7 \
    php7-bcmath \
    php7-curl \
    php7-intl \
    php7-json \
    php7-mbstring \
    php7-opcache \
    php7-gd \
    php7-bz2 \
    php7-zip \
    php7-xml \
    php7-xdebug \
    php7-pdo \
    php7-pdo_mysql \
    php7-ctype \
    php7-session \
    php7-phar \
    php7-tokenizer \
    php7-zlib \
    php7-dom \
    php7-xmlwriter \
    php7-simplexml \
    php7-posix \
    php7-fileinfo \
    && rm -rf /var/cache/apk/*

COPY php.ini /etc/php7/conf.d/50-setting.ini

RUN rm /etc/php7/conf.d/xdebug.ini
ENV XDEBUGINI_PATH=/etc/php7/conf.d/50-xdebug.ini
COPY xdebug.ini $XDEBUGINI_PATH
RUN echo "xdebug.remote_host="`/sbin/ip route|awk '/default/ { print $3 }'` >> $XDEBUGINI_PATH

# composer
# --------
RUN apk add --no-cache git curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer


RUN export PATH=$PATH:/root/.composer/vendor/bin \
    && export COMPOSER_DISABLE_XDEBUG_WARN=1

ARG GITHUB_OAUTH_TOKEN=false
RUN if [ ${GITHUB_OAUTH_TOKEN} != false ]; then \
    composer config -g github-oauth.github.com ${GITHUB_OAUTH_TOKEN} \
;fi

CMD ["php", "-a"]
