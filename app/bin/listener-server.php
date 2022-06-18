<?php
require dirname(__DIR__) . '/vendor/autoload.php';
use BinanceProducer\Manager\BinanceManager;

$rabbitURL = '10.2.113.62';
$val = getopt("u:c:");




print_r($val['u']);
print_r($val['c']);


$manager = new BinanceManager($val['u'], $val['c']);
$manager->start();
