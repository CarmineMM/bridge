<?php

namespace App\Controllers;

use Core\Foundation\BaseController;
use Core\Foundation\Request;

class HomeController extends BaseController
{
    public function __invoke(Request $request)
    {
        return $this->view('welcome', [
            'foo' => 'bar',
        ]);
    }
}
