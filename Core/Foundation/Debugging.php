<?php

namespace Core\Foundation;

use Core\Exception\ExceptionHandle;
use Core\Loaders\Config;
use Core\Loaders\HtmlInject;
use Core\Support\Debug;
use Core\Support\Conversion\UnitsConversion;
use Core\Translate\Lang;

class Debugging
{
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
        $alpine = Debug::resources['alpine'];
        $render = new Render;
        $render->config_view_path = 'framework.view_path';



        $renderHtml = $htmlInject
            ->headBot("
                <link rel='stylesheet' href='/{$cssDebug}'>
                <script src='/{$jsDebug}' defer></script>
                <script src='/{$alpine}' defer></script>
            ")
            ->bodyBot($render->view('debugbar', ['app' => $app]))
            ->getHtml();

        // echo "<br><br>Execution Time: {$execution_time} seconds";
        // echo "<br>Memory Usage: " . UnitsConversion::make($memory, 'Bytes')->display('KB');
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

        foreach (ExceptionHandle::getList() as $item) {
            $exceptions[] = [
                'code' => $item->getCode(),
                'message' => $item->getMessage(),
                'file' => $item->getFile(),
                'line' => $item->getLine(),
            ];
        }

        $list = [
            'config' => [
                'title' => Lang::_get('configurations', [], 'Config'),
                'elements' => Config::all()->toArray(),
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
                'title' => Lang::_get('exceptions', [], 'Config'),
                'elements' => $exceptions,
                'tabs' => [],
            ]
        ];

        return $list;
    }
}
