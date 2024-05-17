<?php

/**
 * Bridge - A PHP small Framework 
 * 
 * @package Bridge
 * @version 1.0.0
 */

$otro =  shell_exec('cd .. && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer');

$installComposer =  shell_exec('cd.. && composer install');
echo "<pre>$installComposer</pre>";

$pave =  shell_exec('cd .. && ls');
echo "<pre>$pave</pre>";

// require_once __DIR__ . '/../vendor/autoload.php';


if (phpversion() < 8.2) {
    die("Your PHP version must be 8.2 or higher to run <b>Minos Golding</b>. Current version: " . phpversion());
}

define('PUBLIC_PATH', dirname(__FILE__));

// \Core\Foundation\Application::run();
