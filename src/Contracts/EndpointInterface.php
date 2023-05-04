<?php

namespace Farzai\KApi\Contracts;

use Farzai\KApi\Http\RequestInterface;
use Farzai\KApi\Http\ResponseInterface;

interface EndpointInterface
{
    /**
     * Send the request.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface;
}
