<?php

use Core\Exception\ExceptionHandle;

dump(ExceptionHandle::getList());
?>

<div x-show="selectedOption === 'exceptions'" class="regular-content">
    lista de excepciones
</div>