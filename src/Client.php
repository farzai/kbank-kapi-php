<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Farzai\KApi\Contracts\ClientInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @property-read \Farzai\KApi\OAuth2\Endpoint $oauth2
 * @property-read \Farzai\KApi\QrPayment\Endpoint $qrPayment
 */
class Client implements ClientInterface
{
    const CLIENT_NAME = 'kapi-php';

    const VERSION = '1.0.0';

    /**
     * Consumer credentials encoded in base64.
     */
    public string $consumer = '';

    /**
     * Sandbox mode.
     */
    public bool $sandbox = false;

    /**
     * Create a new client instance.
     */
    public function __construct(
        protected PsrClientInterface $client
    ) {
        //
    }

    /**
     * OAuth2 endpoint.
     *
     * @return \Farzai\KApi\OAuth2\Endpoint
     */
    public function createOAuth2Endpoint()
    {
        return new OAuth2\Endpoint($this);
    }

    /**
     * Check if the client is in sandbox mode.
     */
    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    /**
     * Get the consumer credentials.
     * (base64 encoded)
     */
    public function getConsumer(): string
    {
        return $this->consumer;
    }

    /**
     * Send the request.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->prepareRequest($request);

        return $this->client->sendRequest($request);
    }

    /**
     * Get the base uri.
     */
    public function getBaseUri(): string
    {
        $host = $this->isSandBox()
            ? 'openapi-sandbox.kasikornbank.com'
            : 'openapi.kasikornbank.com';

        return 'https://'.$host;
    }

    /**
     * Prepare the request.
     */
    protected function prepareRequest(RequestInterface $request): void
    {
        $uri = $request->getUri();
        if (! empty($uri->getHost())) {
            return;
        }

        $uri->withHost($this->getBaseUri());

        if (! $request->hasHeader('Authorization')) {
            $request->withHeader('Authorization', 'Basic '.$this->consumer);
        }

        $request->withHeader('User-Agent', self::CLIENT_NAME.'/'.self::VERSION);
    }

    public function __get($name)
    {
        $methodName = 'create'.ucfirst($name).'Endpoint';

        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }

        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        throw new \Exception('Undefined property: '.static::class.'::$'.$name);
    }
}
