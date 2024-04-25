<?php

use Core\Loaders\Config;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Config::get('app.name') ?> - 404 Not Found</title>
    <style>
        :root {
            --debug-bar-primary: #fe5f00;
            --debug-bar-dark: #653239;
            --debug-bar-light: #fcfcfc;
            --debug-bar-light-2: #f3eded;
            --debug-bar-secondary: #416788;
            --debug-bar-success: #ffbf00;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: var(--debug-bar-light);
        }

        h1 {
            font-size: 10rem;
            color: var(--debug-bar-primary);
        }

        hgroup p {
            font-size: 1.2rem;
            color: var(--debug-bar-secondary);
        }

        hgroup {
            text-align: center;
        }
    </style>
</head>

<body>
    <hgroup>
        <h1>429</h1>
        <p>Rate limit exceeded</p>
    </hgroup>
</body>

</html>