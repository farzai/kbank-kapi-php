<?php

require_once __DIR__.'/../vendor/autoload.php';

use DateTime;
use Farzai\KApi\ClientBuilder;
use Farzai\KApi\OAuth2\Requests\RequestAccessToken;
use Farzai\KApi\QrPayment\Requests\RequestThaiQRCode;

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

// Send request to get access token
$response = $client->oauth2->sendRequest(new RequestAccessToken());

if (! $response->isSuccessfull()) {
    throw new Exception('Failed to get access token.');
}

$accessToken = $response->json('access_token');

$currentDate = new DateTime();

// Send request to get QR code
$request = new RequestThaiQRCode();

$request
    ->withToken($accessToken)

    // Required
    ->setMerchant(id: 'BEV06000080200')
    ->setPartner(
        partnerTransactionID: 'RGH001030118001',
        partnerID: 'POS001',
        partnerSecret: 'PPsaiu7890yyatcionmsp01ooYY46789',
        requestDateTime: $currentDate,
    )

    // Optional
    ->setTerminal(id: '09000107') 
;

$response = $client->qrPayment->sendRequest($request);
