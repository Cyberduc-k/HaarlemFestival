<html lang="en">
<head>
    <title>Account</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/account.css" />
    <link type="text/css" rel="stylesheet" href="/css/innerNav.css" />
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <main>
        <nav>
            <ul>
                <li>
                    <a href='/programme'>Programme</a>
                </li>
                <li>
                    <a href='/user/edit'>Edit my information</a>
                </li>
                <li>
                    <a href='/user/change_avatar'>Change Avatar</a>
                </li>
                <li>
                    <a href="/cart">Cart</a>
                </li>
            </ul>
        </nav>

        <?php
            echo "<h1 style='float: left'>Welcome ".$firstName."!</h1>";
            echo "<img class='avatar' src=".$avatar." alt='avatar' style='float: left; margin: 40px 0px 0px 40px'>";
        ?>
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
