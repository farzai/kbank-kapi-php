<?php

namespace Farzai\KApi\Http;

use Farzai\KApi\Contracts\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class ServerRequest implements ServerRequestInterface
{
    protected PsrServerRequestInterface $request;

    private $jsondecoded;

    /**
     * Create a new server request instance.
     */
    public function __construct(PsrServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Get the header.
     */
    public function header($name, $default = null): ?string
    {
        return $this->request->getHeaderLine($name) ?: $default;
    }

    /**
     * Get the request method.
     */
    public function method(): string
    {
        return $this->request->getMethod();
    }

    /**
     * Get json body.
     */
    public function json($key = null, $default = null)
    {
        if (! $this->jsondecoded) {
            $this->jsondecoded = @json_decode($this->request->getBody()->getContents(), true) ?: null;
        }

        if (is_null($key)) {
            return $this->jsondecoded;
        }

        return $this->jsondecoded[$key] ?? $default;
    }

    /**
     * Determine if the request is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->json('statusCode') === '00'
            && $this->json('errorCode') === null;
    }

    /**
     * Get psr request.
     */
    public function getPsrRequest(): PsrServerRequestInterface
    {
        return $this->request;
    }
}
