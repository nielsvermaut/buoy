FROM php:7.2-cli

ADD . /app

ENTRYPOINT ["/app/bin/buoy"]
CMD ["/app/bin/buoy"]