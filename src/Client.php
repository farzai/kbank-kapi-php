<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Farzai\KApi\Contracts\ClientInterface;
use Farzai\KApi\Contracts\OAuth2AccessTokenRepositoryInterface;
use Farzai\KApi\Entities\AccessToken;
use Farzai\KApi\Http\Request;
use Farzai\KApi\OAuth2\Requests\RequestAccessToken;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

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
        protected PsrClientInterface $client,
        protected OAuth2AccessTokenRepositoryInterface $tokenRepository
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
    public function sendRequest(PsrRequestInterface $request): PsrResponseInterface
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
    public function prepareRequest(PsrRequestInterface $request): PsrRequestInterface
    {
        $uri = $request->getUri();

        if (empty($uri->getHost())) {
            $request = $request->withUri(new Uri($this->getUri().$uri->getPath()));
        }

        if ($request->hasHeader('Authorization')) {
            $value = $request->getHeaderLine('Authorization');

            // If the value contains ":access_token:" then we will replace it with the access token.
            if (strpos($value, Request::STUB_ACCESS_TOKEN) !== false) {
                $accessToken = $this->resolveAccessToken();

                $request = $request->withHeader(
                    'Authorization',
                    str_replace(Request::STUB_ACCESS_TOKEN, $accessToken->access_token, $value),
                );
            }
        } else {
            $request = $request->withHeader('Authorization', 'Basic '.$this->consumer);
        }

        $request = $request->withHeader('User-Agent', self::CLIENT_NAME.'/'.self::VERSION);

        return $request;
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

    /**
     * Grant a new access token.
     */
    protected function requestNewAccessToken(): AccessToken
    {
        $response = $this->oauth2->sendRequest(
            new RequestAccessToken()
        );

        if (! $response->isSuccessfull()) {
            throw new \Exception('Unable to grant a new access token.');
        }

        return new AccessToken($response->json());
    }

    public function __get($name)
    {
        // Forwards the property to the endpoint.
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
     * Resolve the access token.
     */
    private function resolveAccessToken(): AccessToken
    {
        $accessToken = $this->tokenRepository->retrieve();

        if (empty($accessToken) || $accessToken->isExpired()) {
            $accessToken = $this->requestNewAccessToken();

            $this->tokenRepository->forget();
            $this->tokenRepository->store($accessToken);
        }

        return $accessToken;
    }
}
