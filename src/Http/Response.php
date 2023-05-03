<?php

namespace Farzai\KApi\Http;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class Response implements ResponseInterface
{
    protected PsrResponseInterface $response;

    /**
     * @var mixed
     */
    protected $jsonDecorded;

    /**
     * Create a new response instance.
     */
    public function __construct(PsrResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Return the response status code.
     */
    public function statusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * Return the response body.
     */
    public function body(): string
    {
        return $this->response->getBody()->getContents();
    }

    /**
     * Return the response headers.
     */
    public function headers(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * Check if the response is successfull.
     */
    public function isSuccessfull(): bool
    {
        return $this->statusCode() >= 200 && $this->statusCode() < 300;
    }

    /**
     * Return the psr response.
     */
    public function toPsrResponse(): PsrResponseInterface
    {
        return $this->response;
    }

    /**
     * Return the json decoded response.
     */
    public function json(?string $key = null): mixed
    {
        if ($this->jsonDecorded !== false && is_null($this->jsonDecorded)) {
            $this->jsonDecorded = @json_decode($this->response->getBody()->getContents(), true);
        }

        if ($this->jsonDecorded === false) {
            return null;
        }

        if (is_null($key)) {
            return $this->jsonDecorded;
        }

        return $this->jsonDecorded[$key] ?? null;
    }
}
