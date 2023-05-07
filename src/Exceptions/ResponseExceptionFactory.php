<?php

namespace Farzai\KApi\Exceptions;

use Farzai\KApi\Contracts\ResponseInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Throwable;

class ResponseExceptionFactory
{
    /**
     * Create a new exception instance.
     */
    public static function create(ResponseInterface $response, ?Throwable $previous = null)
    {
        $statusCode = $response->statusCode();

        if ($statusCode >= 400 && $statusCode < 500) {
            return new BadResponseException(
                static::getErrorMessage($response),
                $response->getPsrRequest(),
                $response->getPsrResponse(),
                $previous,
            );
        }

        if ($statusCode >= 500 && $statusCode < 600) {
            return new ServerException(
                static::getErrorMessage($response),
                $response->getPsrRequest(),
                $response->getPsrResponse(),
                $previous,
            );
        }

        return new ClientException(
            static::getErrorMessage($response),
            $response->getPsrRequest(),
            $response->getPsrResponse(),
            $previous,
        );
    }

    /**
     * Get the error message from the response.
     */
    public static function getErrorMessage(ResponseInterface $response): string
    {
        $message = $response->body();

        if ($response->json() !== null) {
            return $response->json('message')
                ?: $response->json('error_message')
                ?: $response->json('error_msg')
                ?: $response->json('error_description')
                ?: $response->json('error')
                ?: 'Unknown error';
        }

        return $message;
    }
}
