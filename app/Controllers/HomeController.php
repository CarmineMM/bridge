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

        // $user->query('SELECT * FROM users'),
        dump($user->create([
            'name' => [
                'first' => 'John',
                'last' => 'Doe',
            ],
            'email' => 'j@mail.como',
            'password' => '123456'
        ])->toSQL());

        return $this->view('welcome', [
            'foo' => 'bar',
        ]);
    }
}
