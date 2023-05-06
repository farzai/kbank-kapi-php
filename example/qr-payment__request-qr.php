<?php

require_once __DIR__.'/../vendor/autoload.php';

use Farzai\KApi\ClientBuilder;
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

$currentDate = new \DateTime('now', new \DateTimeZone('Asia/Bangkok'));
$transactionId = 'TS'.time();

// Send request to get QR code
$request = new RequestThaiQRCode();

$request
    // Required
    ->setMerchant(id: 'BEV06000080200')
    ->setPartner(
        partnerTransactionID: $transactionId,
        partnerID: 'POS001',
        partnerSecret: 'PPsaiu7890yyatcionmsp01ooYY46789',
        requestDateTime: $currentDate,
    )
    ->setAmount(amount: 100)
    ->setReferences('INV001')

    // Optional
    ->setTerminal(id: '09000107')
    ->setCurrency(currency: 'THB') // Default is THB
    ->setMetadata([
        'แก้วเบียร์ 40บ.',
        'เหล้าขาว 60บ.',
    ]);

$response = $client->qrPayment->sendRequest($request);

// Print request
echo '<pre>';
print_r([
    'data' => $response->json(),
]);
echo '</pre>';

// Array
// (
//     [data] => Array
//         (
//             [partnerTxnUid] => 1683384864
//             [partnerId] => POS001
//             [statusCode] => 00
//             [errorCode] =>
//             [errorDesc] =>
//             [accountName] => Kasikorn API
//             [qrCode] => xxxxxxxxxxxxxxx
//             [sof] => Array
//                 (
//                     [0] => PP
//                 )
//         )
// )
