<?php

namespace Farzai\KApi\Http;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

interface ResponseInterface
{
    /**
     * Return the response status code.
     */
    public function statusCode(): int;

    /**
     * Return the response body.
     */
    public function body(): string;

    /**
     * Return the response headers.
     */
    public function headers(): array;

    /**
     * Check if the response is successfull.
     */
    public function isSuccessfull(): bool;

    /**
     * Return the psr response.
     */
    public function toPsrResponse(): PsrResponseInterface;

    /**
     * Return the json decoded response.
     */
    public function json(?string $key = null): mixed;
}
