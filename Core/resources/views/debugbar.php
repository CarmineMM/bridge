<?php

use Core\Foundation\Application;
use Core\Foundation\Context;
use Core\Foundation\Request;
use Core\Loaders\Config;
use Core\Support\Conversion\UnitsConversion;

$request = Request::make();
$context = new Context;

$end_time = microtime(true);
$execution_time = round(($end_time - $app->start_time), 5);
$memory = UnitsConversion::make(memory_get_usage() - $app->memory, 'byte');

// Obtener las query's
$queries = $context->getState('bridge:query', []);

// Función para convertir segundos en Mili-segundos
function secondsToMilliseconds($seconds)
{
    return round($seconds * 1000, 3);
}

?>
<footer id="debug-bar" x-data="debugbar" :class="{ open: bodyOpen }">
    <div class="debugbar-header">
        <h2 class="debugbar-title">
            <a href="https://github.com/CarmineMM/bridge" target="_blank"><?= Application::FrameworkName ?></a>
        </h2>
        <ul class="debugbar-options">
            <!-- Lista de configuraciones -->
            <li>
                <button type="button" @click="selectOption('config')">
                    Configurations
                </button>
            </li>

            <li>
                <button type="button" @click="selectOption('query')">
                    Queries (<?= count($queries) ?>)
                </button>
            </li>
        </ul>
        <ul class="debugbar-resume">
            <li title="<?= $memory->display('KB') ?>"><?= $memory->show() ?></li>
            <li title="<?= $execution_time ?> seconds"><?= secondsToMilliseconds($execution_time) ?> <small>ms</small></li>
            <li title="<?= $request->url ?>">/<?= $request->uri ?></li>
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
        <!-- Lista de configuraciones -->
        <?php $this->include('debugbar-options.configurations'); ?>

        <!-- Lista de query's -->
        <div x-show="selectedOption === 'query'" class="debugbar-body-item">
            <ul>
                <?php foreach ($queries as $value) : ?>
                    <li class="list-item item-query">
                        <div style="width: 75%;">
                            <p><?= $value['query'] ?></p>
                        </div>
                        <div style="width: 7%;">
                            <p><?= UnitsConversion::make($value['memory'], 'byte')->show() ?></p>
                        </div>
                        <div style="width: 7%;">
                            <p><?= secondsToMilliseconds($value['time']) ?>ms</p>
                        </div>
                        <div style="width: 10%;">
                            <p>
                                <span title="Connection"><?= $value['connection'] ?></span>:<span title="Driver"><?= $value['driver'] ?></span>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</footer>