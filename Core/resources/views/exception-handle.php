<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bridge Exception Handle</title>
    <style>
        header {
            background-color: var(--debug-bar-primary);
            display: flex;
            justify-content: space-between;
            color: white;
            padding: 0.6em 1.5em;
            margin-bottom: 2em;
        }

        header h1 {
            font-size: 1.2em;
            text-transform: uppercase;
            letter-spacing: 0.2em;
        }

        .container {
            max-width: 80%;
            margin: 0 auto;
        }

        .container-exception {
            background-color: var(--debug-bar-light-2);
            padding: 1.5em;
            border-radius: 0.2em;
        }
    </style>
</head>

<body class="exception-handle">
    <header>
        <h1>Bridge</h1>
    </header>

    <div class="container">
        <div class="container-exception">
            <h2><?= $error->getMessage() ?></h2>
        </div>
    </div>
</body>

</html>