# KBank API - PHP SDK (Unofficial)
### (Work in progress)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/farzai/kapi-sdk.svg?style=flat-square)](https://packagist.org/packages/farzai/kapi-sdk)
[![Tests](https://img.shields.io/github/actions/workflow/status/farzai/kbank-kapi-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/farzai/kapi-sdk/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/farzai/kapi-sdk.svg?style=flat-square)](https://packagist.org/packages/farzai/kapi-sdk)

Welcome to the KBank API SDK for PHP! This SDK simplifies the process of integrating with KBank APIs by providing a set of pre-built functions that handle authentication, requests, and responses. With this SDK, developers can easily build applications that leverage KBank services, such as payment processing, account management, and more. The KBank API SDK for PHP is designed to be easy to use, with clear documentation and examples to get you started quickly.


### Documentation
See full documentation at [https://farzai.gitbook.io/kbank-kapi-php](https://farzai.gitbook.io/kbank-kapi-php)


## Requirements
```
{
    "php": "^8.0",
    "ext-json": "*",
    "ext-curl": "*"
}
```



## Support products

| Done | Products                   | 
| --- |-----------------------------------|
|  -  | [QR Payment](https://apiportal.kasikornbank.com/product/public/All/QR%20Payment/Introduction/GETTING%20STARTED) | 
|  -  | [Bill Payment](https://apiportal.kasikornbank.com/product/public/All/Bill%20Payment/Introduction/GETTING%20STARTED) | 
|  -  | [Slip Verification](https://apiportal.kasikornbank.com/product/public/All/Slip%20Verification/Introduction/Getting%20Started) | 
|  -  | [Inward Remittance](https://apiportal.kasikornbank.com/product/public/All/Inward%20Remittance/Introduction/GETTING%20STARTED) | 
|  -  | [KGP Merchant Payment Platform](https://apiportal.kasikornbank.com/product/public/All/KGP%20Merchant%20Payment%20Platform/Introduction/Getting%20Started) | 
|  -  | [Corporate Fund Transfer](https://apiportal.kasikornbank.com/product/public/All/Corporate%20%20Fund%20Transfer/Introduction/Getting%20Started) |



## Please note
This project is still under development.
- [x] OAuth2 Authentication
- [x] Setup environment and test suite
- [ ] Documentation
- [ ] Implement products
- [ ] Webhook callback capture
- [ ] Code coverage must >= 80%
- [ ] Add support two-way SSL authentication
- [ ] Release v1.0.0


## Installation

You can install the package via composer:

```bash
composer require farzai/kapi-sdk
```

## Usage

This is a simple example of qr payment.
```php

use Farzai\KApi\ClientBuilder;
use Farzai\KApi\OAuth2\Requests as OAuth2Requests;
use Farzai\KApi\QrPayment\Requests as QrPaymentRequests;

// Create client instance
$client = ClientBuilder::make()
    ->setConsumer("<YOUR_CONSUMER_ID>", "<YOUR_CONSUMER_SECRET>")
    ->asSandbox()
    ->build();


// This SDK will automatically generate oauth2 access token for you.
// You can ignore this step !!
// $accessToken = $client->oauth2
//     ->sendRequest(new OAuth2Requests\RequestAccessToken())
//     ->throw()
//     ->json('access_token');
```

Next, Build qr code payment request
```php
$yourTransactionId = 'TS'.time();

$request = new QrPaymentRequests\RequestThaiQRCode();
$request
    // Required
    ->setMerchant(id: '<YOUR_MERCHANT_ID>')
    ->setPartner(
        partnerTransactionID: $yourTransactionId,
        partnerID: '<YOUR_PARTNER_ID>',
        partnerSecret: '<YOUR_PARTNER_SECRET>',
        requestDateTime: new \DateTime('now'),
    )
    ->setAmount(100)
    ->setReferences('<YOUR_ORDER_ID>')
    // or ->setReferences('<reference1>', '<reference2>', '<reference3>', '<reference4>')

    // Optional
    ->setTerminal('<YOUR_TERMINAL_ID>')
    ->setCurrency('THB') // Default is THB
    ->setMetadata([
        'แก้วเบียร์ 40บ.',
        'เหล้าขาว 60บ.',
    ]);

// Send request
$response = $client->qrPayment->sendRequest($request);

// Print response data
print_r($response->json());

// Or, you can get response data with specific key
echo $response->json('partnerTxnUid'); // Output: xxxxxxx

```

Sometime, you may want to handle webhook from payment notification service
```php
use Farzai\KApi\QrPayment;

// This SDK will automatically validate your request.
$result = $client->processWebhook(new QrPayment\PaymentNotificationCallback);

$result->json() // returns: array
$result->json('partnerTxnUid') // returns: string
$result->isSuccessful() // returns: bool
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [parsilver](https://github.com/parsilver)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
