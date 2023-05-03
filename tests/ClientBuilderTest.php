<?php

use Farzai\KApi\Client;
use Farzai\KApi\ClientBuilder;
use Psr\Http\Client\ClientInterface;

it('can build a client', function () {
    $client = ClientBuilder::create()
        ->setConsumer('thisisid', 'thisissecret')
        ->build();

    expect($client)->toBeInstanceOf(Client::class);
    expect($client)->toBeInstanceOf(ClientInterface::class);
});

it('should consumer encode with base64 valid', function () {
    $client = ClientBuilder::create()
        ->setConsumer('thisisid', 'thisissecret')
        ->build();

    expect($client->getConsumer())->toBe('dGhpc2lzaWQ6dGhpc2lzc2VjcmV0');
});
