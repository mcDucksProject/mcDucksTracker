FROM laravelsail/php80-composer:latest

RUN apt-get update \
    && apt-get install -y libgmp-dev  \
    && docker-php-ext-install gmp
