<?php

declare(strict_types=1);

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\PluginClient;
use JustSteveKing\Webhooks\Enums\Method;
use JustSteveKing\Webhooks\Tests\Stubs\ResourceWebhook;
use JustSteveKing\Webhooks\Webhook;
use Psr\Http\Message\ResponseInterface;

it('can build a stubbed webhook class', function (): void {
    expect(
        new ResourceWebhook(),
    )->toBeInstanceOf(Webhook::class);
});

it('returns the webhook correctly', function (): void {
    $webhook = new ResourceWebhook();

    expect(
        $webhook->buildPayload(),
    )->toBeArray()->toEqual(['foo' => 'bar'])->and(
        $webhook->url(),
    )->toBeString()->and(
        $webhook->headers(),
    )->toBeArray()->toHaveKeys(
        keys: ['Content-Type', 'Signature'],
    )->and(
        $webhook->method(),
    )->toBeInstanceOf(Method::class)->toEqual(Method::POST)->and(
        $webhook->plugins(),
    )->toBeArray()->toBeEmpty();
});

it('can build the plugin client', function (): void {
    expect(
        (new ResourceWebhook())->pluginClient(),
    )->toBeInstanceOf(PluginClient::class);
});

it('can build the http client', function (): void {
    expect(
        (new ResourceWebhook())->httpClient(),
    )->toBeInstanceOf(HttpMethodsClient::class);
});

it('can send a request', function (): void {
    expect(
        (new ResourceWebhook())->send(),
    )->toBeInstanceOf(ResponseInterface::class);
});
