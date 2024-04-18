<?php

use Core\Exception\ExceptionHandle;

$elements = [];

foreach (ExceptionHandle::getList() as $item) {
    $elements[] = [
        'code' => $item->getCode(),
        'message' => $item->getMessage(),
        'file' => $item->getFile(),
        'line' => $item->getLine(),
    ];
}

// dump($elements, ExceptionHandle::getList());

?>

<div x-show="selectedOption === 'exceptions'" class="regular-content">
    <ul>
    </ul>
</div>