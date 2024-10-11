<?php

namespace Core\FullBridge;

use Core\Foundation\ServiceProvider;
use Core\Loaders\Config;

class FullBridgeProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(bool $consoleMode): void
    {
        // Register middleware
        Config::addConfig('middleware.web', [FullBridgeMiddleware::class]);
    }
}
