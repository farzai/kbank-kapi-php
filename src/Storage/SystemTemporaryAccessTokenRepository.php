<?php

namespace Farzai\KApi\Storage;

use Farzai\KApi\Contracts\OAuth2AccessTokenRepositoryInterface;
use Farzai\KApi\Entities\AccessToken;

class SystemTemporaryAccessTokenRepository implements OAuth2AccessTokenRepositoryInterface
{
    /**
     * The storage path.
     */
    protected string $path;

    /**
     * Create a new storage instance.
     */
    public function __construct(
        protected string $prefix
    ) {
        // Check if the system temporary directory is readable and writable.
        if (! is_readable(sys_get_temp_dir()) || ! is_writable(sys_get_temp_dir())) {
            throw new \RuntimeException('The system temporary directory is not readable or writable.');
        }

        $this->path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'kapi-php__'.$this->prefix;
    }

    /**
     * Get the access token.
     */
    public function retrieve(): ?AccessToken
    {
        // Get the access token from the storage.
        // If the access token is not found, return null.
        if (false === ($content = @file_get_contents($this->path))) {
            return null;
        }

        // Decode the access token.
        if (false === ($data = @json_decode($content, true))) {
            return null;
        }

        return new AccessToken($data);
    }

    /**
     * Store the access token.
     */
    public function store(AccessToken $token): void
    {
        // Encode the access token.
        $data = json_encode($token->toArray());

        // Store the access token.
        file_put_contents($this->path, $data);
    }

    /**
     * Forget the access token.
     */
    public function forget(): void
    {
        // Remove the access token from the storage.
        @unlink($this->path);
    }
}
