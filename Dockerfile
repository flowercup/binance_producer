FROM php:7.4-cli
COPY ./app  /usr/src/binance_producer
WORKDIR /usr/src/binance_producer
#ENTRYPOINT [ "php", "bin/listener-server.php" ]
CMD php bin/listener-server.php -u10.2.113.62 -cBTCUSDT -cEOSBTC -cETHUSDT
#CMD['-u10.2.113.62 -cBTCUSDT -cEOSBTC -cETHUSDT']
