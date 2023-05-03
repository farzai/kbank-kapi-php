<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Farzai\KApi\Http\Response;
use Farzai\KApi\Http\ResponseInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

abstract class AbstractEndpoint
{
    protected ClientInterface $client;

    /**
     * Create a new client instance.
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new request
     */
    protected function createRequest(string $method, string $uri, array $headers = [], string $body = null): RequestInterface
    {
        return new Request(
            method: $method,
            uri: $uri,
            headers: $headers,
            body: $body
        );
    }

    /**
     * Send the request.
     */
    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        return new Response($this->client->sendRequest($request));
    }
}
