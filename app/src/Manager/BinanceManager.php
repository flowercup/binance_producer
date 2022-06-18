<?php

namespace BinanceProducer\Manager;

use BinanceProducer\Api\API;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class BinanceManager
{
    /**
     * @var API
     */
    private $binanceApi;
    /**
     * @var AMQPStreamConnection
     */
    private $rabbitConnection;
    /**
     * @var AbstractChannel|AMQPChannel
     */
    private $rabbitChannel;
    /**
     * @var array
     */
    private $coins;

    public function __construct(string $rabbitUrl = '10.2.113.62', array $coins = ['BTCUSDT', 'EOSBTC'])
    {
        $this->binanceApi = new API();
        $this->rabbitConnection = new AMQPStreamConnection($rabbitUrl, 5672, 'guest', 'guest');
        $this->coins = $coins;
        print_r($this->coins);
    }

    public function start() {
        $this->rabbitChannel = $this->rabbitConnection->channel();
        $this->binanceApi->kline($this->coins, "5m", function($api, $symbol, $chart, $eventTime) {
            $this->rabbitPost($symbol, $chart, $eventTime);
            echo "tick...";
            echo "\n";
            echo $symbol;
            echo "\n";

            $endpoint = strtolower( $symbol ) . '@kline_' . "5m";
            //$api->terminate( $endpoint );
        });

    }

    private function rabbitPost($symbol, $chart, $eventTime) {
        $data = $this->formatData($symbol, $chart, $eventTime);
        $msg = new AMQPMessage($data);
        print_r($data);
        $this->rabbitChannel->basic_publish($msg, 'ingestor_exchange', 'exchange_queue');
    }

    private function formatData($symbol, $chart, $eventTime) {

        $priceFloat = floatval($chart->c);
        $currentValue = intval($priceFloat * 100);
        $toSend = [
            'timestamp' => $eventTime,
            'currentValue' => $currentValue,
            'exchangeName' => 'BINANCE',
            'coin' => $symbol,
            'volume' => $chart->v

        ];
        return json_encode($toSend);
    }
}
