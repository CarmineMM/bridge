<?php

namespace Core\Loaders;

use Core\Foundation\Application;
use Core\Foundation\Filesystem;
use Core\Loaders\FilesLoad\ConfigAppFile;
use Core\Loaders\FilesLoad\ConfigDatabaseFile;
use Core\Loaders\FilesLoad\ConfigResourcesFile;
use Core\Loaders\FilesLoad\ConfigRoutesFile;
use Core\Loaders\FilesLoad\ConfigSecurityFile;
use Core\Support\Collection;

/**
 * Carga los archivos de configuración de la aplicación
 * 
 * @package Bridge Framework
 * @version 1.0.0
 */
class Config
{
    use ConfigAppFile, ConfigRoutesFile, ConfigResourcesFile, ConfigDatabaseFile, ConfigSecurityFile;

    /**
     * Archivos de configuración
     */
    private array $files = [
        'app'    => 'app/config/app.php',
        'routes' => 'app/config/routes.php',
        'resources' => 'app/config/resources.php',
        'database' => 'app/config/database.php',
        'security' => 'app/config/security.php',
    ];

    /**
     * List config
     *
     * @var array|Collection
     */
    public Collection|array $config = [];

    /**
     * Instance
     */
    public static $instance = null;

    /**
     * Indica si ya se cargaron las configuraciones
     */
    public bool $wasLoad = false;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->config = [
            'framework' => [
                'name'        => 'Bridge Framework',
                'version'     => Application::FrameworkVersion,
                'consoleMode' => false,
                'view_path'   => Filesystem::rootPath(['Core', 'resources', 'views'])
            ]
        ];
    }

    /**
     * Instance singleton
     */
    public static function getInstance(): Config
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->config['app'] = self::$instance->appConfig();
            self::$instance->config['routes'] = self::$instance->routesConfig();
            self::$instance->config['resources'] = self::$instance->resourcesConfig();
            self::$instance->config['database'] = self::$instance->databaseConfig();
            self::$instance->config['security'] = self::$instance->securityConfig();
        }

        return self::$instance;
    }

    /**
     * Pre cargar las configuraciones del sistema
     * 
     * @lifecycle 4: Load Config
     */
    public static function load(): void
    {
        $config = static::getInstance();

        if ($config->wasLoad) {
            return;
        }

        $add = [];

        foreach ($config->files as $path => $file) {
            $getFile = Filesystem::rootPath(explode('/', $file));

            if (file_exists($getFile)) {
                $add[$path] = include $getFile;
            }
        }

        $config->config = new Collection(
            $config->filter(array_merge($config->config, $add)),
            false
        );

        self::$instance = $config;
        self::$instance->wasLoad = true;
    }

    /**
     * Obtiene todas las configuraciones
     */
    public static function all(): Collection
    {
        return self::getInstance()->config;
    }

    /**
     * Obtiene una configuración del sistema
     */
    public static function get(string $index, mixed $default = null): mixed
    {
        return self::getInstance()->config->get($index, $default);
    }

    /**
     * Filtro para los configuraciones
     */
    private function filter(array $filter): array
    {
        $fill = [];

        foreach ($filter as $key => $value) {
            if ($key === 'app') {
                $value['url'] = trim($value['url'], '/');
            }

            $fill[$key] = is_string($value)
                ? match ($value) {
                    'true'  => true,
                    'false' => false,
                    default => $value
                }
                : $value;
        }

        return $fill;
    }
}
