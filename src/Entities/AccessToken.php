<?php

namespace Farzai\KApi\Entities;

use DateTime;

/**
 * @property string $access_token
 * @property string $client_id
 * @property int $expires_in The expiration time for the access token. Expressed in seconds.
 * @property \DateTime $issued_at The time the access token was issued.
 * @property string $scope The scope (if any) associated with the access token.
 * @property string $status The status of the access token (e.g., approved or revoked).
 * @property string $token_type It is a required parameter which is assigned by the KBank Open API and specifies the type of token.
 * @property array $developer
 */
class AccessToken extends AbstractEntity
{
    public function isExpired(): bool
    {
        if ($this->issued_at === null || $this->expires_in === null) {
            return true;
        }

        return $this->issued_at->getTimestamp() + $this->expires_in < time();
    }

    protected function getExpiresInAttribute($value)
    {
        return (int) $value;
    }

    protected function getIssuedAtAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTime) {
            return $value;
        }

        if (is_numeric($value)) {
            return DateTime::createFromFormat('U', $value);
        }

        if (is_array($value)) {
            return DateTime::createFromFormat('Y-m-d H:i:s\.u', $value['date'], new \DateTimeZone($value['timezone']));
        }

        return DateTime::createFromFormat(DateTime::ATOM, $value);
    }
}
