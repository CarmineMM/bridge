<?php

namespace App\Controllers;

use App\Models\User;
use Core\Foundation\BaseController;
use Core\Foundation\Context;
use Core\Foundation\Request;
use Core\Loaders\Config;

class HomeController extends BaseController
{
    public function __invoke()
    {
        $user = new User;

        return $this->view('welcome', [
            'foo' => 'bar',
        ]);
    }
}
