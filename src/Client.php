<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Farzai\KApi\Contracts\ClientInterface;
use GuzzleHttp\Psr7\Uri;
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
        return $this->client->sendRequest(
            $this->prepareRequest($request)
        );
    }

    /**
     * Get the base uri.
     */
    public function getUri(): string
    {
        $host = $this->isSandBox()
            ? 'openapi-sandbox.kasikornbank.com'
            : 'openapi.kasikornbank.com';

        return 'https://'.$host;
    }

    /**
     * Prepare the request.
     */
    protected function prepareRequest(RequestInterface $request): RequestInterface
    {
        $uri = $request->getUri();

        if (empty($uri->getHost())) {
            $request = $request->withUri(new Uri($this->getUri().$uri->getPath()));
        }

        if (! $request->hasHeader('Authorization')) {
            $request = $request->withHeader('Authorization', 'Basic '.$this->consumer);
        }

        $request = $request->withHeader('User-Agent', self::CLIENT_NAME.'/'.self::VERSION);

        return $request;
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

    /**
     * OAuth2 endpoint.
     *
     * @return \Farzai\KApi\OAuth2\Endpoint
     */
    protected function createOAuth2Endpoint()
    {
        return new OAuth2\Endpoint($this);
    }

    /**
     * QrPayment endpoint.
     *
     * @return \Farzai\KApi\QrPayment\Endpoint
     */
    protected function createQrPaymentEndpoint()
    {
        return new QrPayment\Endpoint($this);
    }
}
