<?php

namespace App\Controllers;

use Core\Foundation\BaseController;
use Core\Foundation\RateLimit\Driver\Session;

class HomeController extends BaseController
{
    public function __invoke()
    {
        return $this->view('welcome', [
            'foo' => 'bar',
        ]);
    }
}
