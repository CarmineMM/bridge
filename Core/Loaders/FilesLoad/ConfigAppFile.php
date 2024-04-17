<?php

namespace Core\Loaders\FilesLoad;

use Core\Foundation\Application;

/**
 * Archivos de configuración 'app'
 */
trait ConfigAppFile
{
    /**
     * Default app config
     */
    protected array $app = [
        'name'            => Application::FrameworkName,
        'debug'           => true,
        'env'             => 'local',
        'url'             => 'http://localhost:8080',
        'locale'          => 'es',
        'fallback_locale' => 'en',
        'locale_folder'   => 'app/locale',
        'providers'       => [
            \Core\Foundation\ServiceProvider::class,
        ]
    ];

    /**
     * Return app Config
     */
    public function appConfig(): array
    {
        return $this->app;
    }
}
