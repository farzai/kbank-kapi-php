<?php

namespace Farzai\KApi\Contracts;

use Farzai\KApi\Entities\AccessToken;

interface OAuth2AccessTokenRepositoryInterface
{
    /**
     * Get the access token.
     */
    public function retrieve(): ?AccessToken;

    /**
     * Store the access token.
     */
    public function store(AccessToken $token): void;

    /**
     * Forget the access token.
     */
    public function forget(): void;
}
