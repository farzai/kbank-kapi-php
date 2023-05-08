<?php

require_once __DIR__.'/../vendor/autoload.php';

use Farzai\KApi\ClientBuilder;
use Farzai\KApi\QrPayment;

// This is example credentials (Don't worry, it's not real credentials)
// (ref: https://apiportal.kasikornbank.com/product/public/All/QR%20Payment/Try%20API/OAuth%202.0)
// Please use your own credentials
$config = [
    'consumer_key' => 'a2FzaWtvcm5iYW5rdXNlcg==',
    'consumer_secret' => 'a2FzaWtvcm5iYW5rcGFzc3dvcmQ=',
];

// Create client instance
$client = ClientBuilder::make()
    ->setConsumer($config['consumer_key'], $config['consumer_secret'])
    ->asSandbox()
    ->build();

$result = $client->processWebhook(new QrPayment\PaymentNotificationCallback);

print_r($result->json());
