<?php

declare(strict_types=1);

namespace JustSteveKing\Webhooks;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\PluginClient;
use Http\Client\Common\PluginClientFactory;
use Http\Client\Exception;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use JustSteveKing\Webhooks\Enums\Method;
use Psr\Http\Message\ResponseInterface;

abstract class Webhook
{
    public function pluginClient(): PluginClient
    {
        return (new PluginClientFactory())->createClient(
            client: HttpClientDiscovery::find(),
            plugins: $this->plugins(),
        );
    }

    public function httpClient(): HttpMethodsClient
    {
        return new HttpMethodsClient(
            httpClient: $this->pluginClient(),
            requestFactory: Psr17FactoryDiscovery::findRequestFactory(),
            streamFactory: Psr17FactoryDiscovery::findStreamFactory(),
        );
    }

    /**
     * Send the HTTP Request.
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function send(): ResponseInterface
    {
        return $this->httpClient()->send(
            method: $this->method()->value,
            uri: $this->url(),
            headers: $this->headers(),
            body: \Safe\json_encode(
                value: $this->buildPayload(),
                flags: JSON_THROW_ON_ERROR,
            ),
        );
    }

    /**
     * Return any Headers you need to add to the request.
     *
     * @return array
     */
    abstract public function headers(): array;

    /**
     * Build the Request Payload.
     *
     * @return array
     */
    abstract public function buildPayload(): array;

    /**
     * Return the SRL you want to use for your webhook.
     *
     * @return string
     */
    abstract public function url(): string;

    /**
     * Return the HTTP Method used for thie request.
     *
     * @return Method
     */
    abstract public function method(): Method;

    /**
     * Return any HTTP Plugins you need to add to your Request.
     *
     * @return array
     */
    abstract public function plugins(): array;
}
