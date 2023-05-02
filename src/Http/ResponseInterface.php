<?php

namespace Farzai\KApi\Http;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

interface ResponseInterface
{
    /**
     * Return the psr response.
     */
    public function toPsrResponse(): PsrResponseInterface;

    /**
     * Return the json decoded response.
     */
    public function json(?string $key = null): mixed;
}
