FROM php:7.2-cli

RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) mysqli

ADD . /app

WORKDIR /project

ENTRYPOINT ["/app/bin/buoy"]
CMD ["/app/bin/buoy"]