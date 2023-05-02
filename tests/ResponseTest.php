<?php

use Farzai\KApi\Http\Response;
use GuzzleHttp\Psr7\Response as PsrResponse;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

it('should return the psr response', function () {
    $response = new Response(
        new PsrResponse()
    );

    expect($response->toPsrResponse())->toBeInstanceOf(PsrResponseInterface::class);
});

it('should return the json decoded response', function () {
    $response = new Response(
        new PsrResponse(
            200,
            [],
            json_encode([
                'foo' => 'bar',
            ])
        )
    );

    expect($response->json())->toBeArray();
    expect($response->json('foo'))->toBe('bar');
});

it('should return null if the json is invalid', function () {
    $response = new Response(
        new PsrResponse(
            200,
            [],
            'invalid json'
        )
    );

    expect($response->json())->toBeNull();
    expect($response->json('foo'))->toBeNull();
});

it('should return null if the json key is not exists', function () {
    $response = new Response(
        new PsrResponse(
            200,
            [],
            json_encode([
                'foo' => 'bar',
            ])
        )
    );

    expect($response->json('bar'))->toBeNull();
});