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
            ->asForm();
    }

    /**
     * Set version of the API.
     * (v1, v2)
     *
     * @param  string  $version
     */
    public function useApiVersion($version)
    {
        if ($version === 'v1') {
            $this->uri = '/oauth2/token';

            return;
        }

        $this->uri = "/{$version}/oauth/token";

        return $this;
    }
}
