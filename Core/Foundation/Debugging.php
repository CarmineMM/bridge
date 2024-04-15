<?php

namespace Core\Foundation;

use Core\Loaders\Config;
use Core\Loaders\HtmlInject;
use Core\Support\Debug;
use Core\Support\UnitsConversion;

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
}
