<?php

namespace App\FullBridge;

use Core\FullBridge\Component;

class Test extends Component
{
    public string $name = 'PROBANDO PARA VE';

    public function render(): string
    {
        // html
        return <<<HTML
            <div>
                <h1 class="title">Hello World</h1>
                <input bridge:model="name" />
            </div>
        HTML;
    }
}
