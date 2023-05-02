<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

class ClientBuilder
{
    /**
     * Consumer credentials
     *
     * @var string
     */
    private $consumer;

    /**
     * Sandbox
     */
    private bool $sandbox = false;

    /**
     * SSL certificate
     *
     * @var array [$cert,] is the name of a file containing a PEM formatted certificate,
     *              $password if the certificate requires a password
     */
    private array $sslCert;

    /**
     * SSL key
     *
     * @var array [$key,] is the name of a file containing a private SSL key,
     *              $password if the private key requires a password
     */
    private array $sslKey;

    /**
     * SSL verification
     * Enable or disable the SSL verfiication (default is true)
     */
    private bool $sslVerification = true;

    private ?ClientInterface $client;

    private ?LoggerInterface $logger;

    /**
     * Start building the client
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Build the client
     *
     * @param <string, mixed> $config
     * @return Client
     */
    public static function fromConfig(array $config): self
    {
        $builder = self::create();

        if (isset($config['sandbox']) && $config['sandbox'] === true) {
            $builder->asSandbox();
        }

        if (isset($config['ssl'])) {
            if (isset($config['ssl']['cert'])) {
                $builder->setSslCert($config['ssl']['cert']);
            }

            if (isset($config['ssl']['key'])) {
                $builder->setSslKey($config['ssl']['key']);
            }

            if (isset($config['ssl']['verify'])) {
                $builder->setSslVerification($config['ssl']['verify']);
            }
        }

        return $builder;
    }

    /**
     * Set consumer id and secret
     */
    public function setConsumer(string $id, string $secret): self
    {
        $this->consumer = base64_encode($id.':'.$secret);

        return $this;
    }

    /**
     * Enable the sandbox
     */
    public function asSandbox(): self
    {
        $this->sandbox = true;

        return $this;
    }

    /**
     * Disable the sandbox
     */
    public function asProduction(): self
    {
        $this->sandbox = false;

        return $this;
    }

    /**
     * Enable the two way SSL
     *
     * @param  string  $password
     */
    public function withTwoWaySsl(string $cert, string $key, string $password = null): self
    {
        $this->sslCert = [$cert, $password];
        $this->sslKey = [$key, $password];
        $this->sslVerification = true;

        return $this;
    }

    /**
     * Set the SSL certificate
     *
     * @param  string  $password
     */
    public function setSslCert(string $cert, string $password = null): self
    {
        $this->sslCert = [$cert, $password];

        return $this;
    }

    /**
     * Set the SSL key
     *
     * @param  string  $password
     */
    public function setSslKey(string $key, string $password = null): self
    {
        $this->sslKey = [$key, $password];

        return $this;
    }

    /**
     * Set the SSL verification
     */
    public function setSslVerification(bool $verify): self
    {
        $this->sslVerification = $verify;

        return $this;
    }

    /**
     * Set the client
     */
    public function setClient(ClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Set the logger
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Build the client
     */
    public function build(): Client
    {
        return new Client(
            $this->client,
            $this->logger,
            $this->consumer,
            $this->sslCert,
            $this->sslKey,
            $this->sslVerification
        );
    }

    private function __construct()
    {
        $this->client = null;
        $this->logger = null;
    }
}
