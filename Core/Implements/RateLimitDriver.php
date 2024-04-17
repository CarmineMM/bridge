<?php

namespace Core\Implements;

interface RateLimitDriver
{
    public function check(string $key): bool;
    public function increment(string $key): void;
    public function reset(string $key): void;
}
