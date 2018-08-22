FROM php:7.1.20-cli-stretch

RUN apt update && apt install -y aspell aspell-el libpspell-dev  icu-devtools libicu-dev && \
    docker-php-source extract && \
    docker-php-ext-install pspell intl && \
    docker-php-source delete

COPY . /klitikizer

WORKDIR /klitikizer
CMD ["./vendor/bin/phpunit", "tests"]