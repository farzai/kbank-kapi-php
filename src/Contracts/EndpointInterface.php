<?php

namespace Farzai\KApi\Contracts;

interface EndpointInterface
{
    /**
     * Send the request.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface;
}
