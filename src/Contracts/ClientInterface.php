<?php

namespace Farzai\KApi\Contracts;

use Psr\Http\Client\ClientInterface as PsrClientInterface;

interface ClientInterface extends PsrClientInterface
{
    /**
     * Get the consumer key.
     */
    public function getConsumer(): string;

    /**
     * Check if the client is in sandbox mode.
     */
    public function isSandbox(): bool;
}
