<?php

namespace Farzai\KApi\OAuth2\Requests;

use Farzai\KApi\Http\Request;

class RequestAccessToken extends Request
{
    /**
     * Create a new request instance.
     */
    public function __construct()
    {
        $this
            ->post('/v2/oauth/token')
            ->asForm()
            ->withPayload([
                'grant_type' => 'client_credentials',
            ]);
    }
}
