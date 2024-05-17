<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bridge</title>
</head>

<body>
    <h1>Bridge</h1>
    <?php
    $get = shell_exec('ls');
    echo "<pre>get: $get</pre>";
    ?>
</body>

</html>