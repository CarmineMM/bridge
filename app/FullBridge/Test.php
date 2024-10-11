<?php

namespace App\FullBridge;

use Core\FullBridge\Component;

class Test extends Component
{
    public function render(): string
    {
        // html
        return <<<HTML
            <div>
                <h1 class="title">Hello World</h1>
            </div>
        HTML;
    }
}
