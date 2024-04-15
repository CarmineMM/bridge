<?php

namespace App\Controllers;

use App\Models\User;
use Core\Foundation\BaseController;
use Core\Foundation\Context;
use Core\Foundation\Request;

class HomeController extends BaseController
{
    public function __invoke(Request $request)
    {
        $user = new User;
        $user->all();
        $user->all(['id', 'name']);
        $user->all(['id']);

        dump((new Context)->allState());
        return $this->view('welcome', [
            'foo' => 'bar',
        ]);
    }
}
