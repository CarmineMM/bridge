<?php

use Core\Support\Conversion\TimeConversion;
use Core\Support\Conversion\UnitsConversion;

?>
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
                    <p><?= TimeConversion::make($value['time'], 's')->show() ?></p>
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