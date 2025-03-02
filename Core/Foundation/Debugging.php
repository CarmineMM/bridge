<?php

namespace Core\Foundation;

use Core\Exception\ExceptionHandler;
use Core\Loaders\Config;
use Core\Loaders\HtmlInject;
use Core\Support\Debug;
use Core\Translate\Lang;

class Debugging
{
    /**
     * Debug routes para excluir
     */
    public static $debugRoutes = [
        '/__bridge-debugbar-css',
        '/__bridge-debugbar-js',
        '/__bridge-deps-js'
    ];

    /**
     * Rendering el debug bar
     *
     * @lifecycle 12: Render Debug Bar
     */
    public static function renderDebugBar(Application $app, string &$renderHtml = ''): void
    {
        $htmlInject = new HtmlInject($renderHtml);
        $cssDebug = Debug::resources['css'];
        $jsDebug = Debug::resources['js'];
        $deps = Debug::resources['js-deps'];
        $render = new Render;
        $render->config_view_path = 'framework.view_path';

        $renderHtml = $htmlInject
            ->headBot("
                <link rel='stylesheet' href='/{$cssDebug}'>
                <script src='/{$jsDebug}' defer></script>
                <script src='/{$deps}' defer></script>
            ")
            ->bodyBot($render->view('debugbar', ['app' => $app]))
            ->getHtml();
    }

    /**
     * Formatea la estructura de la lista de cosas a debugbar,
     * esto para hacer que javascript haga el renderizado
     *
     * @return array
     */
    public static function formatDebugList(): array
    {
        $queries = (new Context)->getState('bridge:query', []);
        $exceptions = [];

        foreach (ExceptionHandler::getList() as $item) {
            $exceptions[] = $item;
        }

        // Filtra las configuraciones que no deberían ser mostradas
        $allConfig = Config::all()->filter(function ($item, $key) {
            if ($key === 'fullbridge' && $item['enabled'] === false) {
                return false;
            }
            return $item;
        });

        $list = [
            'bridge' => [
                'title' => Application::FrameworkName,
                'elements' => [
                    'Bridge Version' => Application::FrameworkVersion,
                    'Bridge Mode' => $allConfig->get('framework.consoleMode') ? 'Console' : 'Web',
                ],
                'tabs' => [],
            ],
            'config' => [
                'title' => Lang::_get('configurations', [], 'Config'),
                'elements' => static::splitFormatFiles($allConfig->toArray(), ['view_path', 'providers', 'migration_handler', 'namespace', 'middleware.web']),
                'tabs' => [],
            ],
            'queries' => [
                'title' => 'Queries (' . count($queries) . ')',
                'elements' => $queries,
                'tabs' => [],
            ],
            'context' => [
                'title' => 'Context',
                'elements' => [],
                'tabs' => [
                    'store' => [
                        'title' => 'Context Store',
                        'elements' => (new Context)->allStore(),
                    ],
                    'state' => [
                        'title' => 'Context State',
                        'elements' => (new Context)->allState(),
                    ],
                ],
            ],
            'exceptions' => [
                'title' => Lang::_get('exceptions', [], 'Config') . ' (' . count($exceptions) . ')',
                'elements' => static::splitFormatFiles($exceptions, ['file']),
                'tabs' => [],
            ]
        ];

        return $list;
    }

    /**
     * El frontend no lee el carácter '\' por lo que se deben de dividir,  
     * en un arreglo y posteriormente unirlos en el front.
     * Esta función busca los string coincidentes y los separa.
     */
    public static function splitFormatFiles(array $innerArray, array $coincidences)
    {
        foreach ($innerArray as $innerKey => $values) {
            foreach ($values as $key => $path) {
                if (in_array($key, $coincidences)) {
                    if (is_string($path)) {
                        // $innerArray[$innerKey][$key] = explode('\\', $path);
                        $innerArray[$innerKey][$key] = str_replace('\\', '/', $path);
                    } else if (is_array($path)) {
                        $innerArray[$innerKey][$key] = array_map(function ($item) {
                            // return explode('\\', $item);
                            return str_replace('\\', '/', $item);
                        }, $path);
                    }
                }
            }
        }

        return $innerArray;
    }
}
