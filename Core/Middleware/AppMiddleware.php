<?php

namespace Core\Middleware;

interface AppMiddleware
{
    public function handle(): void;
}
