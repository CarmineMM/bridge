<?php

namespace App\Controllers;

use Core\Foundation\BaseController;
use Core\Foundation\RateLimit\RateLimit;

class HomeController extends BaseController
{
    public function __invoke()
    {
        return $this->view('welcome', [
            'foo' => 'bar',
        ]);
    }
}
