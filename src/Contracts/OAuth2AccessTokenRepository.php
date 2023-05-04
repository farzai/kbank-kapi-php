<?php

namespace Farzai\KApi\Contracts;

use Farzai\KApi\Entities\AccessToken;

interface OAuth2AccessTokenRepository
{
    /**
     * Get the access token.
     */
    public function retrieve(EndpointInterface $endpoint): ?AccessToken;

    /**
     * Store the access token.
     */
    public function store(EndpointInterface $endpoint, AccessToken $token): void;

    /**
     * Forget the access token.
     */
    public function forget(EndpointInterface $endpoint): void;
}
