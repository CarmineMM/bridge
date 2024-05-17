<?php

/**
 * Bridge - A PHP small Framework 
 * 
 * @package Bridge
 * @version 1.0.0
 */
echo 'probar si funciona';
// require_once __DIR__ . '/../vendor/autoload.php';

if (phpversion() < 8.2) {
    die("Your PHP version must be 8.2 or higher to run <b>Minos Golding</b>. Current version: " . phpversion());
}

define('PUBLIC_PATH', dirname(__FILE__));

// \Core\Foundation\Application::run();
