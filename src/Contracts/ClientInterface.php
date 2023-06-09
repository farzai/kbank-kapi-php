<?php

namespace Farzai\KApi\Contracts;

use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

interface ClientInterface
{
    /**
     * Get the consumer key.
     */
    public function getConsumer(): string;

    /**
     * Check if the client is in sandbox mode.
     */
    public function isSandbox(): bool;

    /**
     * Get the client timezone.
     */
    public function getTimezone(): string;

    /**
     * Send the request.
     */
    public function sendRequest(PsrRequestInterface $request): ResponseInterface;

    /**
     * Get the PSR client.
     */
    public function getPsrClient(): PsrClientInterface;
}
