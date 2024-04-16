<?php

use Core\Loaders\Config;

?>

<div x-show="selectedOption === 'config'" class="regular-content">
    <?php foreach (Config::all()->toArray() as $config => $values) : if ($config === 'framework' || $config === 'routes') continue; ?>
        <h4><?= ucfirst($config); ?></h4>
        <ul style="margin-bottom: 1.5em;">
            <?php $this->include('components.item-config', ['values' => $values, 'config' => $config]) ?>
        </ul>
    <?php endforeach; ?>
</div>