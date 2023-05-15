---
description: (Work in progress)
---

# KBank API - PHP SDK (Unofficial)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/farzai/kapi-sdk.svg?style=flat-square)](https://packagist.org/packages/farzai/kapi-sdk) [![Tests](https://img.shields.io/github/actions/workflow/status/farzai/kbank-kapi-php/run-tests.yml?branch=main\&label=tests\&style=flat-square)](https://github.com/farzai/kapi-sdk/actions/workflows/run-tests.yml) [![Total Downloads](https://img.shields.io/packagist/dt/farzai/kapi-sdk.svg?style=flat-square)](https://packagist.org/packages/farzai/kapi-sdk)

ไปยังโค้ดต้นฉบับได้ที่ [https://github.com/farzai/kbank-kapi-php](https://github.com/farzai/kbank-kapi-php).

ยินดีต้อนรับสู่ KBank API SDK for PHP! ส่วนช่วยในการเชื่อมต่อกับ KBank APIs โดยมีฟังก์ชันที่สร้างไว้ล่วงหน้าเพื่อจัดการการรับรองความถูกต้อง การร้องขอ และการตอบกลับ ด้วย SDK นี้นักพัฒนาสามารถสร้างแอปพลิเคชันที่ใช้บริการ KBank ได้ง่ายขึ้น เช่น การประมวลผลการชำระเงิน การจัดการบัญชี และอื่น ๆ KBank API SDK for PHP ถูกออกแบบให้ใช้งานง่าย มีเอกสารและตัวอย่างที่ชัดเจนเพื่อให้คุณเริ่มต้นได้ง่ายขึ้น

## การใช้งานคร่าวๆ

```php
use Farzai\KApi\ClientBuilder;
use Farzai\KApi\OAuth2\Requests as OAuth2Requests;
use Farzai\KApi\QrPayment\Requests as QrPaymentRequests;

// สร้าง instance 
$client = ClientBuilder::make()
    ->setConsumer("<YOUR_CONSUMER_ID>", "<YOUR_CONSUMER_SECRET>")
    ->asSandbox()
    ->build();
```

โดยปกติแล้ว SDK จะสร้าง oauth access token ให้คุณโดยอัตโนมัติ, คุณสามารถข้ามขั้นตอนนี้ได้เลย++++

```php
// $accessToken = $client->oauth2
//     ->sendRequest(new OAuth2Requests\RequestAccessToken())
//     ->throw()
//     ->json('access_token');
```

ต่อไป,​ ทำการสร้าง request เพื่อเตรียมคำขอ QR Code

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
```

ทำการส่งคำขอไปยัง API ของ KBank, เราจะได้ `$response` มาเช่นตัวอย่างนี้

```php
$response = $client->qrPayment->sendRequest($request);

// Print response data
print_r($response->json());

// Or, you can get response data with specific key
echo $response->json('partnerTxnUid'); // Output: xxxxxxx
```

ส่วนของ Webhook หากคุณต้องการจัดการ webhook จากบริการการแจ้งเตือนการชำระเงิน

คุณสามารถทำได้ดังนี้

```php
use Farzai\KApi\QrPayment;

// ตรงส่วนนี้, SDK จะเป็นตัวตรวจสอบความถูกต้องของข้อมูลที่ส่งมาให้เอง
$result = $client->processWebhook(new QrPayment\PaymentNotificationCallback);

// คุณสามารถเข้าถึงข้อมูลที่ส่งมาได้ดังนี้
$result->isSuccessful() // returns: bool
$result->json() // returns: array
$result->json('partnerTxnUid') // returns: string
```

