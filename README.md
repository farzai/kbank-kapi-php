# KBank API - PHP SDK (Unofficial)
### (Work in progress)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/farzai/kbank-kapi-php.svg?style=flat-square)](https://packagist.org/packages/farzai/kbank-kapi-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/farzai/kbank-kapi-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/farzai/kbank-kapi-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/farzai/kbank-kapi-php.svg?style=flat-square)](https://packagist.org/packages/farzai/kbank-kapi-php)

Welcome to the KBank API SDK for PHP! This SDK simplifies the process of integrating with KBank APIs by providing a set of pre-built functions that handle authentication, requests, and responses. With this SDK, developers can easily build applications that leverage KBank services, such as payment processing, account management, and more. The KBank API SDK for PHP is designed to be easy to use, with clear documentation and examples to get you started quickly.


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

```php
// 
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
