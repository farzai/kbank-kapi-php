<?php

namespace Farzai\KApi\Contracts;

use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

interface ServerRequestInterface
{
    /**
     * Get the header.
     */
    public function header($name, $default = null): ?string;

    /**
     * Get the request method.
     */
    public function method(): string;

    /**
     * Get json body.
     */
    public function json($key = null, $default = null);

    /**
     * Determine if the request is successful.
     */
    public function isSuccessful(): bool;

    /**
     * Get psr request.
     */
    public function getPsrRequest(): PsrServerRequestInterface;
}
