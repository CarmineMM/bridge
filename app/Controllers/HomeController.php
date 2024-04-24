<?php

namespace App\Controllers;

use App\Models\User;
use Core\Database\Base\DB;
use Core\Foundation\BaseController;
use Core\Foundation\Context;
use Core\Foundation\Request;
use Core\Loaders\Config;

class HomeController extends BaseController
{
    public function __invoke()
    {
        $migrations = DB::make('migrations');

        dump(
            $migrations->where('migration', 'CreateUsersTable')->get()
        );

        return $this->view('welcome', [
            'foo' => 'bar',
        ]);
    }
}
