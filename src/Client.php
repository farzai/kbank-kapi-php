<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Client implements ClientInterface
{
    const CLIENT_NAME = 'kapi-php';

    const VERSION = '1.0.0';

    public function __construct(
        protected ClientInterface $client,
        protected LoggerInterface $logger,
        protected string $consumer,
        protected array $sslCert = [],
        protected array $sslKey = [],
        protected bool $sslVerification = false,
        protected bool $sandbox = false
    ) {
        if ($this->sslVerification) {
            $this->ensureCertificationIsValid();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function oauth(): OAuth2\Endpoint
    {
        return new OAuth2\Endpoint($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * {@inheritDoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $request->withHeader('User-Agent', self::CLIENT_NAME.'/'.self::VERSION);
        $request->withHeader('Authorization', 'Basic '.$this->consumer);

        return $this->client->sendRequest($request);
    }

    protected function ensureCertificationIsValid(): void
    {
        if (count($this->sslCert) !== 2) {
            throw new \InvalidArgumentException('SSL certificate must contain 2 elements');
        }

        if (! is_string($this->sslCert[0])) {
            throw new \InvalidArgumentException('SSL certificate must contain a string as first element');
        }

        if (! is_string($this->sslCert[1])) {
            throw new \InvalidArgumentException('SSL certificate must contain a string as second element');
        }

        if (count($this->sslKey) !== 2) {
            throw new \InvalidArgumentException('SSL key must contain 2 elements');
        }

        if (! is_string($this->sslKey[0])) {
            throw new \InvalidArgumentException('SSL key must contain a string as first element');
        }

        if (! is_string($this->sslKey[1])) {
            throw new \InvalidArgumentException('SSL key must contain a string as second element');
        }
    }
}
