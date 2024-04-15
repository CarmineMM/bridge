<?php


require_once __DIR__ . '/../vendor/autoload.php';

// Path hacia la capeta publica
define('PUBLIC_PATH', dirname(__FILE__));

\Core\Foundation\Application::run();
