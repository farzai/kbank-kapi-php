<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Farzai\KApi\Contracts\ClientInterface;
use Farzai\KApi\Contracts\EndpointInterface as EndpointContract;
use Farzai\KApi\Contracts\RequestInterface;
use Farzai\KApi\Contracts\ResponseInterface;

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
        return $this->client->sendRequest($request);
    }
}
