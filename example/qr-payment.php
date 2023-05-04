<?php

require_once __DIR__.'/../vendor/autoload.php';

use Farzai\KApi\ClientBuilder;

$config = [
    'consumer_key' => env('KBANK_CONSUMER_KEY'),
    'consumer_secret' => env('KBANK_CONSUMER_SECRET'),
];

$client = ClientBuilder::create()
    ->setConsumer($config['consumer_key'], $config['consumer_secret'])
    ->asSandbox()
    ->build();

$response = $client->qrPayment->generateThaiQrCode();
