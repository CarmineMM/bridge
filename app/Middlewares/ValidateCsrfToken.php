<?php

namespace App\Middlewares;

use Core\Foundation\Request;
use Core\Foundation\Security;
use Core\Middleware\AppMiddleware;

class ValidateCsrfToken implements AppMiddleware
{
    public function handle(Request $request, $next): mixed
    {
        if ($request->isMethod('POST') && !Security::ValidateCsrfToken($request)) {
            abort('Invalid CSRF Token', 403);
        }
        return $next($request);
    }
}
