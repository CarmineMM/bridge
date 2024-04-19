<?php

use Core\Support\Str;
?>
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
            max-width: 98%;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .container {
                max-width: 80%;
            }
        }

        .container-exception {
            background-color: var(--debug-bar-light-2);
            padding: 1.5em;
            border-radius: 0.2em;
            margin-bottom: 2em;
        }

        .container-exception h2 {
            font-size: 1.5em;
            margin-bottom: 1em;
        }

        main {
            display: flex;
            justify-content: flex-start;
            gap: 1em;
        }

        @media (min-width: 962px) {
            main {
                gap: 2em;
            }
        }

        main ul {
            width: 20%;
        }

        main ul li {
            margin-bottom: 1em;
        }

        @media (min-width: 962px) {
            main ul {
                width: 10%;
            }
        }

        main ul .regular-button {
            width: 100%;
        }
    </style>
</head>

<body class="exception-handle debug-handle">
    <header>
        <h1><?= $app::FrameworkName ?></h1>
    </header>

    <div class="container" x-data="{ activeTab: 'tracer', request: <?= (new Str(json_encode($request->toArray())))->toJsonHtml() ?> }">
        <div class="container-exception">
            <h2><?= $error->getMessage() ?></h2>
            <p>File: <?= $error->getFile() ?></p>
        </div>
        <main>
            <ul>
                <li>
                    <button @click="activeTab = 'tracer'" type="button" :class="{ active: activeTab ==='tracer' }" class="regular-button">Tracer</button>
                </li>
                <li>
                    <button @click="activeTab = 'request'" type="button" :class="{ active: activeTab ==='request' }" class="regular-button">Request</button>
                </li>
            </ul>
            <div x-show="activeTab === 'tracer'">
                <?= $this->include('exceptions.tracer', ['error' => $error]) ?>
            </div>
            <?= $this->include('exceptions.request') ?>
        </main>
    </div>
</body>

</html>