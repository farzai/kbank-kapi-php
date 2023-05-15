<?php

use Farzai\KApi\Http\Response;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface;

it('should return the psr response', function () {
    $response = new Response(
        \Mockery::mock(PsrRequestInterface::class),
        \Mockery::mock(PsrResponseInterface::class),
    );

    expect($response->getPsrResponse())->toBeInstanceOf(PsrResponseInterface::class);
});

it('should return the json decoded response', function () {
    $response = new Response(
        \Mockery::mock(PsrRequestInterface::class),
        \Mockery::mock(PsrResponseInterface::class)
            ->shouldReceive('getBody')->once()
            ->andReturn(
                \Mockery::mock(StreamInterface::class)
                    ->shouldReceive('getContents')->once()
                    ->andReturn(json_encode([
                        'foo' => 'bar',
                    ]))
                    ->getMock()
            )
            ->getMock()
    );

    expect($response->json())->toBeArray();
    expect($response->json('foo'))->toBe('bar');
});

it('should return null if the json is invalid', function () {
    $response = new Response(
        \Mockery::mock(PsrRequestInterface::class),
        \Mockery::mock(PsrResponseInterface::class)
            ->shouldReceive('getBody')->once()
            ->andReturn(
                \Mockery::mock(StreamInterface::class)
                    ->shouldReceive('getContents')->once()
                    ->andReturn('invalid json')
                    ->getMock()
            )
            ->getMock()
    );

    expect($response->json())->toBeNull();
    expect($response->json('foo'))->toBeNull();
});

it('should return null if the json key is not exists', function () {
    $response = new Response(
        \Mockery::mock(PsrRequestInterface::class),
        \Mockery::mock(PsrResponseInterface::class)
            ->shouldReceive('getBody')->once()
            ->andReturn(
                \Mockery::mock(StreamInterface::class)
                    ->shouldReceive('getContents')
                    ->once()
                    ->andReturn(json_encode([
                        'foo' => 'bar',
                    ]))
                    ->getMock()
            )
            ->getMock()
    );

    expect($response->json())->toBeArray();
    expect($response->json('bar'))->toBeNull();
});

it('should call json decode once', function () {
    $content = \Mockery::mock(StreamInterface::class)
        ->shouldReceive('getContents')->once()
        ->andReturn(json_encode([
            'foo' => 'bar',
        ]))
        ->getMock();

    $psrResponse = \Mockery::mock(PsrResponseInterface::class)
        ->shouldReceive('getBody')->once()
        ->andReturn($content)
        ->getMock();

    $response = new Response(
        \Mockery::mock(PsrRequestInterface::class),
        $psrResponse,
    );

    expect($response->json())->toBeArray();
    expect($response->json())->toBeArray();
});

it('should status code valid', function () {
    $response = new Response(
        \Mockery::mock(PsrRequestInterface::class),
        \Mockery::mock(PsrResponseInterface::class)
            ->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(201)
            ->getMock()
    );

    expect($response->statusCode())->toBe(201);
});

it('should successfull', function () {
    $response = new Response(
        \Mockery::mock(PsrRequestInterface::class),
        \Mockery::mock(PsrResponseInterface::class)
            ->shouldReceive('getStatusCode')
            ->andReturn(200)
            ->getMock()
    );

    expect($response->isSuccessfull())->toBeTrue();
});
