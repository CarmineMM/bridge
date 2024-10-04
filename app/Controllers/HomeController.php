<?php

namespace App\Controllers;

use Core\Foundation\BaseController;

class HomeController extends BaseController
{
    public function __invoke()
    {
        return $this->view('welcome', [
            'foo' => 'bar',
        ]);
    }
}
