copy .env.example to .env

docker build -t binance-producer .

docker run -it --env-file=app/.env --rm --name binance-producer-app binance-producer
