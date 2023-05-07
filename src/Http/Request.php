<?php

namespace Farzai\KApi\Http;

use Farzai\KApi\Contracts\RequestInterface;
use GuzzleHttp\Psr7\Request as GuzzlePsrRequest;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

abstract class Request implements RequestInterface
{
    const STUB_ACCESS_TOKEN = ':access_token:';

    protected $method = 'GET';

    protected $uri = '/';

    /**
     * @var array<string, string>
     */
    protected $headers = [];

    protected array $payload = [];

    /**
     * Create a new request instance.
     */
    public function getBody(): ?string
    {
        if (in_array($this->method, ['GET', 'HEAD'])) {
            return null;
        }

        return match ($this->headers['Content-Type'] ?? null) {
            'application/x-www-form-urlencoded' => http_build_query($this->payload),
            'application/json' => json_encode($this->payload),
            default => json_encode($this->payload),
        };
    }

    /**
     * Get the request headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get the request method.
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Set the request headers.
     */
    public function withHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set the payload.
     */
    public function withPayload(array $payload)
    {
        $this->payload = array_merge($this->payload, $payload);

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
     * Request with bearer token.
     */
    public function withBearerToken(?string $token = null)
    {
        return $this->withToken($token ?: static::STUB_ACCESS_TOKEN);
    }

    /**
     * Set the request method and uri.
     */
    public function to($method, string $uri)
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

    /**
     * Set the request method to POST.
     */
    protected function post(string $uri)
    {
        return $this->to('POST', $uri);
    }

    /**
     * Set the request method to GET.
     */
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

        return $this->withHeader('Content-Type', 'application/json');
    }

    /**
     * Request as form.
     */
    protected function asForm()
    {
        $this->method = 'POST';

        return $this->withHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    /**
     * Request expects JSON.
     */
    protected function expectsJson()
    {
        return $this->withHeader('Accept', 'application/json');
    }
}
