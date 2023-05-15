<?php

namespace Farzai\KApi\Http;

use Farzai\KApi\Contracts\ResponseInterface;
use Farzai\KApi\Exceptions\ResponseExceptionFactory;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class Response implements ResponseInterface
{
    /**
     * @var mixed
     */
    protected $jsonDecoded;

    /**
     * @var string
     */
    protected $content;

    /**
     * Create a new response instance.
     */
    public function __construct(
        protected PsrRequestInterface $request,
        protected PsrResponseInterface $response
    ) {
        $this->content = $this->response->getBody()->getContents();
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
        return $this->content;
    }

    /**
     * Return the response headers.
     *
     * @return array<string, array<string>>
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
     * Return the json decoded response.
     */
    public function json(?string $key = null): mixed
    {
        if (is_null($this->jsonDecoded)) {
            $this->jsonDecoded = @json_decode($this->content, true) ?: false;
        }

        if ($this->jsonDecoded === false) {
            return null;
        }

        if (is_null($key)) {
            return $this->jsonDecoded;
        }

        return $this->jsonDecoded[$key] ?? null;
    }

    /**
     * Throw an exception if the response is not successfull.
     *
     * @param  callable|null  $callback Custom callback to throw an exception.
     * @return $this
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function throw(callable $callback = null)
    {
        $callback = $callback ?? function (ResponseInterface $response, \Exception $e) {
            if (! $this->isSuccessfull()) {
                throw $e;
            }

            return $response;
        };

        return $callback($this, ResponseExceptionFactory::create($this)) ?: $this;
    }

    /**
     * Return the psr request.
     */
    public function getPsrRequest(): PsrRequestInterface
    {
        return $this->request;
    }

    /**
     * Return the psr response.
     */
    public function getPsrResponse(): PsrResponseInterface
    {
        return $this->response;
    }
}
