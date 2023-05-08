<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Farzai\KApi\Contracts\AccessTokenRepositoryInterface;
use Farzai\KApi\Contracts\ClientInterface as KApiClientInterface;
use Farzai\KApi\Contracts\ResponseInterface;
use Farzai\KApi\Contracts\WebhookHandlerInterface;
use Farzai\KApi\Entities\AccessToken;
use Farzai\KApi\Http\Request;
use Farzai\KApi\Http\Response;
use Farzai\KApi\Http\ServerRequest;
use Farzai\KApi\OAuth2\Requests\RequestAccessToken;
use Farzai\KApi\Support\DT;
use Farzai\KApi\Support\Str;
use GuzzleHttp\Psr7\ServerRequest as GuzzleServerRequest;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

/**
 * @property-read \Farzai\KApi\OAuth2\Endpoint $oauth2
 * @property-read \Farzai\KApi\QrPayment\Endpoint $qrPayment
 */
class Client implements KApiClientInterface
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

    public $timezone = 'Asia/Bangkok';

    /**
     * Create a new client instance.
     */
    public function __construct(
        private PsrClientInterface $client,
        private AccessTokenRepositoryInterface $tokenRepository
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
    public function sendRequest(PsrRequestInterface $request): ResponseInterface
    {
        $psrResponse = $this->client->sendRequest(
            $psrRequest = $this->normalizeRequest($request)
        );

        return new Response($psrRequest, $psrResponse);
    }

    /**
     * Handle webhook
     */
    public function processWebhook(WebhookHandlerInterface $webhook)
    {
        return $webhook->handle(new ServerRequest(GuzzleServerRequest::fromGlobals()));
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
    public function normalizeRequest(PsrRequestInterface $request): PsrRequestInterface
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

        if ($request->hasHeader('Host')) {
            $request = $request->withoutHeader('Host');
        }

        return $request;
    }

    /**
     * Get current timezone.
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * Get psr client.
     */
    public function getPsrClient(): PsrClientInterface
    {
        return $this->client;
    }

    /**
     * OAuth2 endpoint.
     *
     * @return \Farzai\KApi\OAuth2\Endpoint
     */
    private function createOAuth2Endpoint()
    {
        return new OAuth2\Endpoint($this);
    }

    /**
     * QrPayment endpoint.
     *
     * @return \Farzai\KApi\QrPayment\Endpoint
     */
    private function createQrPaymentEndpoint()
    {
        return new QrPayment\Endpoint($this);
    }

    /**
     * Grant a new access token.
     */
    private function requestNewAccessToken(): AccessToken
    {
        $response = $this->oauth2->sendRequest(
            new RequestAccessToken()
        );

        if (! $response->isSuccessfull()) {
            throw new \Exception('Unable to grant a new access token');
        }

        return new AccessToken(array_merge($response->json(), [
            'issued_at' => DT::now($this->getTimezone()),
        ]));
    }

    public function __get($name)
    {
        // Forwards the property to the endpoint.
        $methodName = 'create'.ucfirst(Str::camel($name)).'Endpoint';
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
