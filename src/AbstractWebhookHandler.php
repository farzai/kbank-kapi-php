<?php

namespace Farzai\KApi;

use Farzai\KApi\Contracts\ServerRequestInterface;
use Farzai\KApi\Contracts\WebhookHandlerInterface;

abstract class AbstractWebhookHandler implements WebhookHandlerInterface
{
    /**
     * Validate the webhook request.
     */
    protected function validate(ServerRequestInterface $request): void
    {
        //
    }

    /**
     * Handle the webhook request.
     */
    public function handle(ServerRequestInterface $request): ServerRequestInterface
    {
        $this->validate($request);

        return $request;
    }

    protected function method(ServerRequestInterface $request, string $method): self
    {
        if ($request->method() !== $method) {
            throw new \InvalidArgumentException("Invalid request method. Expected: {$method}");
        }

        return $this;
    }

    protected function contentType(ServerRequestInterface $request, string $contentType): self
    {
        if ($request->header('Content-Type') !== $contentType) {
            throw new \InvalidArgumentException("Invalid request content type. Expected: {$contentType}");
        }

        return $this;
    }
}
