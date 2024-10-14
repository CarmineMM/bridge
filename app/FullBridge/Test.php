<?php

namespace App\FullBridge;

use Core\FullBridge\Component;

class Test extends Component
{
    public string $name = 'PROBANDO PARA VE';

    public array $elements = [
        'uno',
        'dos',
        'tres'
    ];

    public function render(): string
    {
        // html
        return <<<HTML
            <div>
                <h1 class="title" bridge:text="name">{$this->name}</h1>
                <input bridge:model="name" />
                <input bridge:model="name" />
                <ul>
                    <template bridge:for="element in elements">
                        <li bridge:text="element"></li>
                    </template>
                </ul>
            </div>
        HTML;
    }
}
