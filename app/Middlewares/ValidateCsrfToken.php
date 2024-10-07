<?php

namespace App\Middlewares;

use Core\Foundation\Request;
use Core\Middleware\AppMiddleware;

class ValidateCsrfToken implements AppMiddleware
{
    public function handle(Request $request, $next): mixed
    {
        abort('Invalid CSRF Token');
        return $next($request);
    }
}
