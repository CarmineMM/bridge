<?php

define('ROOT_PATH', dirname(__FILE__));

echo "ROOT PATH" . ROOT_PATH;
$executeDir = shell_exec('ls');
echo "<pre>Execute: {$executeDir}</pre>";

echo "Required: " . ROOT_PATH . '/public/index.php';

require_once ROOT_PATH . '/public/index.php';
