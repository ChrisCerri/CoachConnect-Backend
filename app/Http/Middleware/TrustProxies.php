<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR |
                         Request::HEADER_X_FORWARDED_HOST |
                         Request::HEADER_X_FORWARDED_PORT |
                         Request::HEADER_X_FORWARDED_PROTO |
                         Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Specify the logic that determines if a request should be considered secure.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldSecure(Request $request)
    {
        // Questo è il comportamento predefinito.
        // Laravel rileverà automaticamente se la richiesta è sicura (HTTPS).
        return $request->secure();
    }
}