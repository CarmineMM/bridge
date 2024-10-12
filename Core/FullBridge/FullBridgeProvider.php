<?php

namespace Core\FullBridge;

use Core\Foundation\CarryThrough;
use Core\Foundation\Filesystem;
use Core\Foundation\Request;
use Core\Foundation\Response;
use Core\Foundation\Router;
use Core\Foundation\ServiceProvider;
use Core\Loaders\Config;
use Core\Loaders\HtmlInject;

class FullBridgeProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(bool $consoleMode, ?Request $request): void
    {
        if ($consoleMode) {
            return;
        }

        // Register middleware
        Config::addConfig('middleware.web', FullBridgeMiddleware::class);

        if (!$request->isAjax) {
            // Register routes
            Router::get('/full-bridge-scripts', function () {
                Response::make()->setHeader('Content-Type', 'text/javascript');
                return file_get_contents(Filesystem::rootPath(['Core', 'FullBridge', 'src', 'component.js']));
            })->name('full-bridge-scripts');
        }

        if (Response::headerIs('Content-Type', 'text/html')) {
            CarryThrough::mutateRender(function (string $current, Request $request) {
                if (strpos($current, '<head>') === false) {
                    return $current;
                }

                $inject = new HtmlInject($current);
                return $inject->headBot('<script src="/full-bridge-scripts"></script>')->getHtml();
            });
        }
    }
}
