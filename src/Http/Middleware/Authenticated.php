<?php

namespace Druc\LaravelWire\Http\Middleware;

use Closure;
use Druc\LaravelWire\EnvironmentConfig;

class Authenticated
{
    private EnvironmentConfig $config;

    public function __construct(EnvironmentConfig $config)
    {
        $this->config = $config;
    }

    public function handle($request, Closure $next)
    {
        if (request()->header('wire-key') !== $this->config->authKey()) {
            abort(403);
        }

        return $next($request);
    }
}
