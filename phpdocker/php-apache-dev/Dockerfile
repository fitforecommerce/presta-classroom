#+++++++++++++++++++++++++++++++++++++++
# Dockerfile for webdevops/php-apache-dev:7.2
#    -- automatically generated  --
#+++++++++++++++++++++++++++++++++++++++

FROM webdevops/php-apache:7.2

ENV WEB_DOCUMENT_ROOT=/app \
    WEB_DOCUMENT_INDEX=index.php \
    WEB_ALIAS_DOMAIN=*.vm \
    WEB_PHP_TIMEOUT=600 \
    WEB_PHP_SOCKET=""
ENV WEB_PHP_SOCKET=127.0.0.1:9000
ENV WEB_NO_CACHE_PATTERN="\.(css|js|gif|png|jpg|svg|json|xml)$"

# COPY conf/ /opt/docker/

RUN set -x \
    # Install development environment
    && wget -O - https://packagecloud.io/gpg.key | apt-key add - \
    && echo "deb https://packages.blackfire.io/debian any main" | tee /etc/apt/sources.list.d/blackfire.list \
    && apt-install --allow-unauthenticated \
        blackfire-php \
        blackfire-agent \
    && pecl channel-update pecl.php.net \
    && pecl install xdebug \
    && echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/xdebug.ini \
    # might try: && docker-php-ext-install -j$(nproc) gd
    # Enable php development services
    && docker-service enable syslog \
    && docker-service enable postfix \
    && docker-service enable ssh \
    && docker-run-bootstrap \
    && docker-image-cleanup