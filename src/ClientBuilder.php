<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Farzai\KApi\Logger\NullLogger;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

class ClientBuilder
{
    /**
     * Consumer credentials
     * (base64 encoded)
     *
     * @var string
     */
    private $consumer;

    /**
     * Sandbox
     */
    private bool $sandbox = true;

    /**
     * SSL certificate
     *
     * @var array [$cert,] is the name of a file containing a PEM formatted certificate,
     *              $password if the certificate requires a password
     */
    private array $sslCert = [];

    /**
     * SSL key
     *
     * @var array [$key,] is the name of a file containing a private SSL key,
     *              $password if the private key requires a password
     */
    private array $sslKey = [];

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
     * @return \Farzai\KApi\ClientBuilder
     */
    public static function fromConfig(array $config): self
    {
        $builder = self::create();

        if (isset($config['consumer_key']) && isset($config['consumer_secret'])) {
            $builder->setConsumer($config['consumer_key'], $config['consumer_secret']);
        }

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
        $this->consumer = base64_encode(
            implode(':', array_map('trim', [$id, $secret]))
        );

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
        $this->sslVerification = true;

        $this->setSslCert($cert, $password);
        $this->setSslKey($key, $password);

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
        if (! $this->client) {
            $this->ensureCertificationIsValid();
        }

        if (! $this->logger) {
            $this->logger = new NullLogger();
        }

        return new Client(
            client: new ClientLoggerAdapter(
                client: $this->client ?? $this->getDefaultClient(),
                logger: $this->logger,
            ),
            consumer: $this->consumer,
            sandbox: $this->sandbox,
        );
    }

    /**
     * Ensure the certification is valid
     *
     * @throws \InvalidArgumentException
     */
    protected function ensureCertificationIsValid(): void
    {
        if (empty($this->sslCert) && empty($this->sslKey)) {
            return;
        }

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

    /**
     * Get the default client
     */
    private function getDefaultClient(): ClientInterface
    {
        return new GuzzleClient([
            'verify' => $this->sslVerification,
            'cert' => $this->sslCert,
            'ssl_key' => $this->sslKey,
        ]);
    }

    private function __construct()
    {
        $this->client = null;
        $this->logger = null;
    }
}
