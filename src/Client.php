<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    const CLIENT_NAME = 'kapi-php';

    const VERSION = '1.0.0';

    public function __construct(
        protected ClientInterface $client,
        protected string $consumer,
        protected bool $sandbox = false
    ) {
        //
    }

    /**
     * OAuth2 endpoint.
     *
     * @return \Farzai\KApi\OAuth2\Endpoint
     */
    public function oauth2()
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
}
