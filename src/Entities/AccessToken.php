<?php

namespace Farzai\KApi\Entities;

/**
 * @property string $access_token
 * @property string $client_id
 * @property int $expires_in The expiration time for the access token. Expressed in seconds.
 * @property string $scope The scope (if any) associated with the access token.
 * @property string $status The status of the access token (e.g., approved or revoked).
 * @property string $token_type It is a required parameter which is assigned by the KBank Open API and specifies the type of token.
 * @property array $developer
 */
class AccessToken extends AbstractEntity
{
    public function isExpired(): bool
    {
        return $this->expires_in + time() < time();
    }

    protected function getExpiresInAttribute($value)
    {
        return (int) $value;
    }
}
