<?php

/**
 * Bridge - A PHP small Framework 
 * 
 * @package Bridge
 * @version 1.0.0
 */

$otro =  shell_exec('cd.. && composer');
if (!$otro) {
    echo 'No se ejecuto el composer';
}
echo "<pre>$otro</pre>";

$heyheyhey =  shell_exec('cd.. && composer install');
if (!$heyheyhey) {
    echo 'No se ejecuto el composer install';
}
echo "<pre>$heyheyhey</pre>";

$otro =  shell_exec('ls');
echo "<pre>$otro</pre>";

$pave =  shell_exec('cd .. && ls');
echo "<pre>$pave</pre>";

// require_once __DIR__ . '/../vendor/autoload.php';


if (phpversion() < 8.2) {
    die("Your PHP version must be 8.2 or higher to run <b>Minos Golding</b>. Current version: " . phpversion());
}

define('PUBLIC_PATH', dirname(__FILE__));

// \Core\Foundation\Application::run();
