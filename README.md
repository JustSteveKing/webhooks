# Webhooks

<!-- BADGES_START -->
[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![Run Tests](https://github.com/JustSteveKing/webhooks/actions/workflows/tests.yml/badge.svg)](https://github.com/JustSteveKing/webhooks/actions/workflows/tests.yml)
[![PHP Version][badge-php]][php]
[![Total Downloads][badge-downloads]][downloads]

[badge-release]: https://img.shields.io/packagist/v/juststeveking/webhooks.svg?style=flat-square&label=release
[badge-license]: https://img.shields.io/packagist/l/juststeveking/webhooks.svg?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/juststeveking/webhooks.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/juststeveking/webhooks.svg?style=flat-square&colorB=mediumvioletred

[packagist]: https://packagist.org/packages/juststeveking/webhooks
[license]: https://github.com/juststeveking/webhooks/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/juststeveking/webhooks
<!-- BADGES_END -->

The simplest way to start sending webhooks in PHP.

## Installation

You can install this library using composer:

```bash
composer require juststeveking/webhooks
```

The next step is to install a OSR compliant package to do the request itself, you will need:

- PSR 7 Request Library
- PSR17 Request and Stream Factories
- PSR18 Http Client


This package will auto-discover these packages you have installed for you, so  you not need to wire anything up.

## Usage

This package is super simple to get started with, all you need to do is build one class and you can start sending webhooks.

```php
use JustSteveKing\Webhooks\Webhook;

class YourWebhook extends Webhook
{
    public function headers(): array
    {
        $signature = hash_hmac(
            algo: 'sha256',
            data: \Safe\json_encode(
                value: $this->buildPayload(),
                flags: JSON_THROW_ON_ERROR,
            ),
            key: 'Your signing key goes here',
        );

        return [
            'Content-Type' => 'application/json',
            'Signature' => $signature,
        ];
    }

    public function buildPayload(): array
    {
        return [
            'foo' => 'bar',
        ];
    }

    public function url(): string
    {
        return 'Your URL goes here.';
    }

    public function method(): Method
    {
        return Method::POST;
    }

    public function plugins(): array
    {
        return [];
    }
}
```

The HTTP Client used for sending your HTTP requests supports plugins, you can [see how these work here](https://docs.php-http.org/en/latest/plugins/index.html).

## Tests

There is a composer script available to run the tests:

```bash
composer test
```

However, if you are unable to run this please use the following command:

```bash
./vendor/bin/pest
```

## Security

If you discover any security related issues, please email juststevemcd@gmail.com instead of using the issue tracker.
