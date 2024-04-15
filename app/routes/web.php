<?php

use App\Controllers\HomeController;
use Core\Foundation\Request;
use Core\Foundation\Router;

Router::get(
    url: '/',
    callback: [HomeController::class]
)->name('home');

Router::get('nosotros', function () {
    return 'NOSOTROS';
});

Router::get('curso/:id/:otro', function (Request $request) {
    return 'EL request es';
});
