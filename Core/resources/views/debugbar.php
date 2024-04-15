<?php

use Core\Foundation\Application;
use Core\Foundation\Request;
use Core\Support\UnitsConversion;

$request = Request::make();

$end_time = microtime(true);
$execution_time = round(($end_time - $app->start_time), 5);
$memory = UnitsConversion::make(memory_get_usage() - $app->memory, 'byte');

// Función para convertir segundos en Mili-segundos
function secondsToMilliseconds($seconds)
{
    return $seconds * 1000;
}

?>
<footer id="debug-bar" x-data="debugbar" :class="{ open: bodyOpen }">
    <div class="debugbar-header">
        <h4 class="debugbar-title"><?= Application::FrameworkName ?></h4>
        <ul class="debugbar-options">
            <template x-for="(opt, key) in options">
                <li>
                    <button type="button" x-text="opt.text" @click="selectOption(key)" />
                </li>
            </template>
        </ul>
        <ul class="debugbar-resume">
            <li title="Approximately used memory <?= $memory->display('KB') ?>"><?= $memory->show() ?></li>
            <li title="Execution time: <?= $execution_time ?> seconds"><?= secondsToMilliseconds($execution_time) ?> <small>ms</small></li>
            <li title="Current url: <?= $request->url ?>">/<?= $request->uri ?></li>
            <li>
                <button type="button" title="Show debugbar option" @click="toggleBody">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up">
                        <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                </button>
            </li>
        </ul>
    </div>
    <div class="debugbar-body" x-show="bodyOpen" x-cloak>
        <h1>Ver un resumen de todo</h1>
    </div>
</footer>