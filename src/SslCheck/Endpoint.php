<?php

namespace Farzai\KApi\SslCheck;

use Farzai\KApi\AbstractEndpoint;
use Psr\Http\Message\ResponseInterface;

class Endpoint extends AbstractEndpoint
{
    public function check(): ResponseInterface
    {
        $req = $this->createRequest(
            method: 'GET',
            uri: '/v2/ssl-check',
        );

        return $this->client->sendRequest($req);
    }
}
