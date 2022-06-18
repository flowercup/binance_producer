FROM php:7.4-cli
COPY ./app  /usr/src/binance_producer
WORKDIR /usr/src/binance_producer
CMD [ "php", "bin/listener-server.php" ]
