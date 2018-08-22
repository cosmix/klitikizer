FROM php:7.1.20-cli-stretch

COPY . /klitikizer

ADD https://getcomposer.org/installer /tmp/composer-setup.php
RUN php /tmp/composer-setup.php --install-dir /usr/local/bin/ --filename composer \
    && rm /tmp/composer-setup.php && \
    apt update && apt install -y aspell aspell-el libpspell-dev  icu-devtools libicu-dev && \
    docker-php-source extract && \
    docker-php-ext-install pspell intl && \
    docker-php-source delete && \
    cd /klitikizer && \
    composer install



WORKDIR /klitikizer
CMD ["./vendor/bin/phpunit", "tests"]