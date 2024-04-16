<?php

use Core\Foundation\Context;

$context = new Context;

?>
<div x-show="selectedOption === 'context'" class="regular-content">
    <div style="display: flex; gap: 1em; margin-bottom: 0.8em;">
        <button @click="tabContext = 'store'" :class="{active: tabContext === 'store'}" type="button" class="regular-button">Context Store</button>
        <button @click="tabContext = 'state'" :class="{active: tabContext === 'state'}" type="button" class="regular-button">Context State</button>
    </div>

    <!-- Ver los store -->
    <ul x-show="tabContext === 'store'">
        <?php $this->include('components.item-config', ['values' => $context->allStore()]) ?>
    </ul>

    <ul x-show="tabContext === 'state'">
        <?php $this->include('components.item-config', ['values' => $context->allState()]) ?>
    </ul>
</div>