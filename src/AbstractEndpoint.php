<?php

declare(strict_types=1);

namespace Farzai\KApi;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

abstract class AbstractEndpoint
{
    protected Client $client;

    public function __construct(Client $client)
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
}
