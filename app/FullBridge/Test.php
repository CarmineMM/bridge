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
                <h1 class="title" bridge:text="name">Hello World</h1>
                <input bridge:model="name" />
                <input bridge:model="name" />
                <button>Increment</button>
                Count: <span></span>
            </div>
        HTML;
    }
}
