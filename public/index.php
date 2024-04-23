<?php

/**
 * Bridge - A PHP small Framework 
 * 
 * @package Bridge
 * @version 1.0.0
 */
require_once __DIR__ . '/../vendor/autoload.php';

// Path hacia la capeta publica
define('PUBLIC_PATH', dirname(__FILE__));

\Core\Foundation\Application::run();
