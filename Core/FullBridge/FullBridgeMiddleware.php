<?php

namespace Core\FullBridge;

use Core\Foundation\Request;
use Core\Middleware\AppMiddleware;

class FullBridgeMiddleware implements AppMiddleware
{
    public function handle(Request $request, $next): mixed
    {
        return $next($request);
    }
}
