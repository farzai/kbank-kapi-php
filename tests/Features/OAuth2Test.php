<?php

use Farzai\KApi\Client;
use Farzai\KApi\ClientBuilder;
use Farzai\KApi\OAuth2\Endpoint as OAuth2Endpoint;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

it('should endpoint valid', function () {
    $client = ClientBuilder::create()
        ->setConsumer('key', 'secret')
        ->build();

    $endpoint = $client->oauth2();

    expect($endpoint)->toBeInstanceOf(OAuth2Endpoint::class);
});

it('should call generate access token success', function () {
    $psrResponse = \Mockery::mock(PsrResponseInterface::class)
        ->shouldReceive('getStatusCode')->once()
        ->andReturn(200)
        ->shouldReceive('getBody')->once()
        ->andReturn(
            \Mockery::mock(\Psr\Http\Message\StreamInterface::class)
                ->shouldReceive('getContents')->once()
                ->andReturn(json_encode([
                    'developer.email' => 'dev-sandbox-openapi@kasikornbank.com',
                    'token_type' => 'Bearer',
                    'client_id' => 'a2FzaWtvcm5iYW5rdXNlcg==',
                    'access_token' => 'a2FzaWtvcm5iYW5rdG9rZW4=',
                    'scope' => '',
                    'expires_in' => '1799',
                    'status' => 'approved',
                ]))
                ->getMock()
        )
        ->getMock();

    $client = \Mockery::mock(Client::class)
        ->makePartial()
        ->shouldAllowMockingProtectedMethods()
        ->shouldReceive('sendRequest')->once()
        ->andReturn($psrResponse)
        ->getMock();

    $response = $client->oauth2()->generateAccessToken();

    expect($response->statusCode())->toBe(200);
    expect($response->json('access_token'))->toBe('a2FzaWtvcm5iYW5rdG9rZW4=');
    expect($response->json('token_type'))->toBe('Bearer');
    expect($response->json('expires_in'))->toBe('1799');
    expect($response->json('status'))->toBe('approved');
});
