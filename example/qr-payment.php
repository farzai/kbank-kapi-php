<?php

require_once __DIR__.'/../vendor/autoload.php';

use Farzai\KApi\ClientBuilder;
use Farzai\KApi\QrPayment\Requests\RequestThaiQRCode;

$config = [
    'consumer_key' => env('KBANK_CONSUMER_KEY'),
    'consumer_secret' => env('KBANK_CONSUMER_SECRET'),
];

$client = ClientBuilder::create()
    ->setConsumer($config['consumer_key'], $config['consumer_secret'])
    ->asSandbox()
    ->build();

$request = new RequestThaiQRCode();

$response = $client->qrPayment->sendRequest($request);
