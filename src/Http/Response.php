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

    public function __construct(PsrResponseInterface $response)
    {
        $this->response = $response;
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
