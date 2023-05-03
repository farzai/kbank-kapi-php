<?php

namespace Farzai\KApi\OAuth2;

use Farzai\KApi\AbstractEndpoint;
use Farzai\KApi\Http\ResponseInterface;

class Endpoint extends AbstractEndpoint
{
    /**
     * Generate an access token.
     */
    public function generateAccessToken(): ResponseInterface
    {
        $req = $this->createRequest(
            method: 'POST',
            uri: '/v2/oauth/token',
            headers: [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        );

        return $this->sendRequest($req);
    }
}
