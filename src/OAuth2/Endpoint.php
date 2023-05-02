<?php

namespace Farzai\KApi\OAuth2;

use Farzai\KApi\AbstractEndpoint;
use Farzai\KApi\Client;
use GuzzleHttp\Psr7\Request;

class Endpoint extends AbstractEndpoint
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function generateAccessToken()
    {
        // $req = new Request(
        //     method: "POST",
        //     uri: "/v2/oauth/token",
        //     headers: [
        //         "Content-Type" => "application/x-www-form-urlencoded",
        //     ],
        // );

        $req = $this->createRequest(
            method: 'POST',
            uri: '/v2/oauth/token',
            headers: [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        );
    }
}
