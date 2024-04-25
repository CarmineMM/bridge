<?php

use App\Controllers\HomeController;
use Core\Foundation\Router;

Router::get('/', [HomeController::class])->name('home');
