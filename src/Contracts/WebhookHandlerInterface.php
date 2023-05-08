<?php

namespace Farzai\KApi\Contracts;

interface WebhookHandlerInterface
{
    /**
     * Handle the webhook request.
     */
    public function handle(ServerRequestInterface $request): ServerRequestInterface;
}
