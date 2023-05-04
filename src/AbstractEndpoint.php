<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Farzai\KApi\Contracts\EndpointInterface as EndpointContract;
use Farzai\KApi\Http\RequestInterface;
use Farzai\KApi\Http\Response;
use Farzai\KApi\Http\ResponseInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;

abstract class AbstractEndpoint implements EndpointContract
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
     * Send the request.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return new Response($this->client->sendRequest($request->toPsrRequest()));
    }
}
