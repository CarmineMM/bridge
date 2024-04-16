<?php

use Core\Foundation\Application;
use Core\Foundation\Context;
use Core\Foundation\Request;
use Core\Support\Conversion\TimeConversion;
use Core\Support\Conversion\UnitsConversion;
use Core\Translate\Lang;

$request = Request::make();
$context = new Context;

$end_time = microtime(true);
$execution_time = round(($end_time - $app->start_time), 5);
$memory = UnitsConversion::make(memory_get_usage() - $app->memory, 'byte');

// Obtener las query's
$queries = $context->getState('bridge:query', []);

?>
<footer id="debug-bar" x-data="debugbar" :class="{ open: bodyOpen }">
    <div class="debugbar-header">
        <h2 class="debugbar-title">
            <a href="https://github.com/CarmineMM/bridge" target="_blank"><?= Application::FrameworkName ?></a>
        </h2>
        <ul class="debugbar-options">
            <!-- Lista de configuraciones -->
            <li>
                <button :class="{ active: selectedOption === 'config' && bodyOpen }" type="button" @click="selectOption('config')">
                    <?= Lang::_get('configurations', [], 'Config') ?>
                </button>
            </li>

            <li>
                <button :class="{ active: selectedOption === 'query' && bodyOpen }" type="button" @click="selectOption('query')">
                    Queries (<?= count($queries) ?>)
                </button>
            </li>

            <li>
                <button :class="{ active: selectedOption === 'context' && bodyOpen }" type="button" @click="selectOption('context')">
                    Context
                </button>
            </li>
        </ul>
        <ul class="debugbar-resume">
            <li title="<?= $memory->display('KB') ?>"><?= $memory->show() ?></li>
            <li title="<?= $execution_time ?> seconds"><?= TimeConversion::make($execution_time, 's')->show() ?></li>
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
        <?php $this->include('debugbar-options.queries', ['queries' => $queries]); ?>
        <!-- Lista de items del context -->
        <?php $this->include('debugbar-options.context'); ?>
    </div>
</footer>