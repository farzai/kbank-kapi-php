<?php

namespace Farzai\KApi\OAuth2;

use Farzai\KApi\AbstractEndpoint;
use Farzai\KApi\Http\ResponseInterface;

final class Endpoint extends AbstractEndpoint
{
    /**
     * Generate an access token.
     */
    public function requestAccessToken(Requests\RequestAccessToken $request): ResponseInterface
    {
        return $this->sendRequest($request);
    }
}
