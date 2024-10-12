<?php

namespace Core\FullBridge;

use Core\Foundation\Filesystem;
use Core\Foundation\Request;
use Core\Foundation\Response;
use Core\Foundation\Router;
use Core\Foundation\ServiceProvider;
use Core\Loaders\Config;

class FullBridgeProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(bool $consoleMode, Request $request): void
    {
        // Register middleware
        Config::addConfig('middleware.web', FullBridgeMiddleware::class);

        if (!$request->isAjax) {
            // Register routes
            Router::get('/full-bridge-scripts', function () {
                Response::make()->setHeader('Content-Type', 'text/javascript');
                return file_get_contents(Filesystem::rootPath(['Core', 'FullBridge', 'src', 'component.js']));
            })->name('full-bridge-scripts');
        }
    }
}
