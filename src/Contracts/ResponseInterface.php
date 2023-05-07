<?php

namespace Farzai\KApi\Contracts;

use Psr\Http\Message\RequestInterface as PsrRequestInterface;
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
     * Return the json decoded response.
     */
    public function json(?string $key = null): mixed;

    /**
     * Throw an exception if the response is not successfull.
     *
     * @param  callable|null  $callback Custom callback to throw an exception.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function throw(callable $callback = null);

    /**
     * Return the psr response.
     */
    public function getPsrResponse(): PsrResponseInterface;

    /**
     * Return the psr request.
     */
    public function getPsrRequest(): PsrRequestInterface;
}
