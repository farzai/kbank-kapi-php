<?php

require_once __DIR__.'/../vendor/autoload.php';

use Farzai\KApi\ClientBuilder;
use Farzai\KApi\OAuth2\Requests as OAuth2Requests;

// This is example credentials (Don't worry, it's not real credentials)
// (ref: https://apiportal.kasikornbank.com/product/public/All/QR%20Payment/Try%20API/OAuth%202.0)
// Please use your own credentials
$config = [
    'consumer_key' => 'a2FzaWtvcm5iYW5rdXNlcg==',
    'consumer_secret' => 'a2FzaWtvcm5iYW5rcGFzc3dvcmQ=',
];

// Create client instance
$client = ClientBuilder::make()
    ->setConsumer($config['consumer_key'], $config['consumer_secret'])
    ->asSandbox()
    ->build();

// Send request to get access token
$response = $client->oauth2->sendRequest(
    new OAuth2Requests\RequestAccessToken()
);

// The response is a JSON object containing the following properties:
echo '<pre>';
print_r([
    'status' => $response->statusCode(),
    'data' => [
        // It is a required parameter in which the KBank Open API accesses the token.
        'access_token' => $response->json('access_token'),

        // The client ID of the registered client app.
        'client_id' => $response->json('client_id'),

        // The email of the developer associated with the registered client app.
        'developer' => $response->json('developer.email'),

        // The expiration time for the access token. Expressed in seconds.
        // Although the ExpiresIn element sets the expiration in milliseconds,
        // in the token response and flow variables, the value is expresed in seconds.
        'expires_in' => $response->json('expires_in'),

        // The scope (if any) associated with the access token.
        'scope' => $response->json('scope'),

        // The status of the access token (e.g., approved or revoked).
        'status' => $response->json('status'),

        // It is a required parameter which is assigned by the KBank Open API
        // and specifies the type of token.
        'token_type' => $response->json('token_type'),
    ],
]);
echo '</pre>';
