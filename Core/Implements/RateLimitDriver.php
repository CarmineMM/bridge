<?php

namespace Core\Implements;

use Core\Foundation\Request;

interface RateLimitDriver
{
    public function check(Request $request): void;
    public function increment(): void;
    public function reset(Request $request): void;
}
