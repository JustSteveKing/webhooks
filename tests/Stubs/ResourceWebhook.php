<?php

declare(strict_types=1);

namespace JustSteveKing\Webhooks\Tests\Stubs;

use JustSteveKing\Webhooks\Enums\Method;
use JustSteveKing\Webhooks\Webhook;

final class ResourceWebhook extends Webhook
{
    public function headers(): array
    {
        $signature = hash_hmac(
            algo: 'sha256',
            data: \Safe\json_encode(
                value: $this->buildPayload(),
                flags: JSON_THROW_ON_ERROR,
            ),
            key: '1234',
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
        return 'https://httpdump.app/dumps/13e0974d-1d41-45d7-b793-04647a892209';
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
