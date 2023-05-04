<?php

namespace Farzai\KApi\Http;

use Psr\Http\Message\RequestInterface as PsrRequestInterface;

interface RequestInterface
{
    /**
     * Get the PSR request.
     */
    public function toPsrRequest(): PsrRequestInterface;
}
