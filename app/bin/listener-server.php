<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use BinanceProducer\Manager\BinanceManager;

$rabbitURL = getenv('RABBIT_URL');
$symbolsString = getenv('SYMBOLS');
if ($rabbitURL === false || $symbolsString === false) {
    print_r('Missing env variables');
}
$symbols = explode (",", $symbolsString);

$val = getopt("u:c:");

$manager = new BinanceManager($rabbitURL, $symbols);
$manager->start();
