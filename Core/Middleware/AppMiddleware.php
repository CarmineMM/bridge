<?php

namespace Core\Middleware;

use Core\Foundation\Request;

interface AppMiddleware
{
    public function handle(Request $request, $next): mixed;
}
