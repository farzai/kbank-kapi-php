<?php

declare(strict_types=1);

namespace Farzai\KApi;

use Farzai\KApi\Contracts\OAuth2AccessTokenRepositoryInterface;
use Farzai\KApi\Logger\NullLogger;
use Farzai\KApi\Storage\SystemTemporaryAccessTokenStorage;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

final class ClientBuilder
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
    private bool $sslVerification = false;

    private ?ClientInterface $client;

    private ?LoggerInterface $logger;

    private ?OAuth2AccessTokenRepositoryInterface $tokenRepository;

    /**
     * Create a new builder instance.
     */
    public static function make(): static
    {
        return new self();
    }

    /**
     * Build the client
     *
     * @return \Farzai\KApi\ClientBuilder
     */
    public static function fromConfig(array $config)
    {
        $builder = new static();

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
    public function setConsumer(string $id, string $secret)
    {
        $this->consumer = base64_encode(
            implode(':', array_map('trim', [$id, $secret]))
        );

        return $this;
    }

    /**
     * Enable the sandbox
     */
    public function asSandbox()
    {
        $this->sandbox = true;

        return $this;
    }

    /**
     * Disable the sandbox
     */
    public function asProduction()
    {
        $this->sandbox = false;

        return $this;
    }

    /**
     * Enable the two way SSL
     *
     * @param  string  $password
     */
    public function withTwoWaySsl(string $cert, string $key, string $password = null)
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
    public function setSslCert(string $cert, string $password = null)
    {
        $this->sslCert = [$cert, $password];

        return $this;
    }

    /**
     * Set the SSL key
     *
     * @param  string  $password
     */
    public function setSslKey(string $key, string $password = null)
    {
        $this->sslKey = [$key, $password];

        return $this;
    }

    /**
     * Set the SSL verification
     */
    public function setSslVerification(bool $verify)
    {
        $this->sslVerification = $verify;

        return $this;
    }

    /**
     * Set the client
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Set the logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Set the token repository
     */
    public function setTokenRepository(OAuth2AccessTokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;

        return $this;
    }

    /**
     * Build the client
     */
    public function build(): Client
    {
        $this->ensureCertificationIsValid();

        $client = new Client(
            client: new ClientLoggerAdapter(
                client: $this->client,
                logger: $this->logger,
            ),
            tokenRepository: $this->tokenRepository ?: new SystemTemporaryAccessTokenStorage(
                prefix: "{$this->consumer}:".($this->sandbox ? 'sandbox' : 'production').':'
            ),
        );

        $client->consumer = $this->consumer;
        $client->sandbox = $this->sandbox;

        return $client;
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
        $options = [];

        if ($this->sslVerification) {
            $options = array_merge($options, [
                'cert' => $this->sslCert,
                'ssl_key' => $this->sslKey,
                'verify' => true,
            ]);
        }

        return new GuzzleClient($options);
    }

    private function __construct()
    {
        $this->logger = new NullLogger();
        $this->tokenRepository = null;
        $this->client = $this->getDefaultClient();
    }
}
