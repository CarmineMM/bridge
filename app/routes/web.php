<?php

use App\Controllers\HomeController;
use Core\Foundation\Router;

Router::get(
    url: '/',
    callback: [HomeController::class]
)->name('home');
