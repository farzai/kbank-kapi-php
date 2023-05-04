<?php

namespace Farzai\KApi\Http;

use GuzzleHttp\Psr7\Request as GuzzlePsrRequest;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

abstract class Request implements RequestInterface
{
    protected $method = 'GET';

    protected $uri = '/';

    /**
     * @var array<string, string>
     */
    protected $headers = [
        'Accept' => 'application/json',
    ];

    protected array $payload = [];

    /**
     * Request as JSON.
     */
    public function asJson()
    {
        if ($this->method === 'GET') {
            $this->method = 'POST';
        }

        $this->headers['Content-Type'] = 'application/json';

        return $this;
    }

    /**
     * Request as form.
     */
    public function asForm()
    {
        $this->method = 'POST';
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';

        return $this;
    }

    /**
     * Request with token.
     */
    public function withToken($token, $type = 'Bearer')
    {
        return $this->withHeader('Authorization', $type.' '.$token);
    }

    /**
     * Create a new request instance.
     */
    public function getBody(): string
    {
        return json_encode($this->payload);
    }

    /**
     * Get the request headers.
     *
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function withHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function withPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function to(string $uri): self
    {
        $this->uri = '/'.ltrim($uri, '/');

        return $this;
    }

    /**
     * Get the PSR request.
     */
    public function toPsrRequest(): PsrRequestInterface
    {
        return new GuzzlePsrRequest(
            method: $this->method,
            uri: $this->uri,
            headers: $this->getHeaders(),
            body: $this->getBody(),
        );
    }
}
