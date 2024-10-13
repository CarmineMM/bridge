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
                <h1 class="title" x-text="name">Hello World</h1>
                <input x-model="name" />
                <input x-model="name" />
                <button>Increment</button>
                Count: <span></span>
            </div>
        HTML;
    }
}
