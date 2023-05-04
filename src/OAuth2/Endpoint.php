<?php

namespace Farzai\KApi\OAuth2;

use Farzai\KApi\AbstractEndpoint;
use Farzai\KApi\Contracts\ResponseInterface;

final class Endpoint extends AbstractEndpoint
{
    /**
     * Generate an access token.
     */
    public function requestAccessToken(Requests\RequestAccessToken $request): ResponseInterface
    {
        $request->withToken($this->client->getConsumer(), 'Basic');

        return $this->sendRequest($request);
    }
}
