<?php

namespace Farzai\KApi\Http;

use Farzai\KApi\Contracts\RequestInterface;
use GuzzleHttp\Psr7\Request as GuzzlePsrRequest;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

abstract class Request implements RequestInterface
{
    protected $method = 'GET';

    protected $uri = '/';

    /**
     * @var array<string, string>
     */
    protected $headers = [];

    protected array $payload = [];

    protected function post(string $uri)
    {
        return $this->to('POST', $uri);
    }

    protected function get(string $uri)
    {
        return $this->to('GET', $uri);
    }

    /**
     * Request as JSON.
     */
    protected function asJson()
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
    protected function asForm()
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
    public function getBody(): ?string
    {
        if ($this->method === 'GET') {
            return null;
        }

        if (isset($this->headers['Content-Type']) && $this->headers['Content-Type'] === 'application/x-www-form-urlencoded') {
            return http_build_query($this->payload);
        }

        if (isset($this->headers['Content-Type']) && $this->headers['Content-Type'] === 'application/json') {
            return json_encode($this->payload);
        }

        return null;
    }

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

    public function to($method, string $uri): self
    {
        $this->method = strtoupper($method);
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
