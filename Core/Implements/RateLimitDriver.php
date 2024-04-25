<?php

namespace Core\Implements;

interface RateLimitDriver
{
    public function check(): void;
    public function increment(): void;
    public function reset(): void;
}
