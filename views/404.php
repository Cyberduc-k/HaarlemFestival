<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>
    <h1>Page Not Found</h1>
    <p><?php echo $_SERVER['REQUEST_URI']; ?></p>
</body>
</html>
