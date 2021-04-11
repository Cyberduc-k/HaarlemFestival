<html lang="en">
<head>
    <title>Reset Password</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/resetPassword.css" />
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <main id="resetPasswordForm">
        <h1>Reset Password</h1>
        <form action="resetPassword.php" method="post">
                <div class="formField">
                    <input type="email" name="email" required placeholder="Email" />
                </div>
                <div>
                    <input id="submitBtn" type="submit" value="Reset Password" class="customButton"/>
                </div>
                <div class="formField red">
                    <p><?php if(isset($_SESSION["passwordError"])){echo $_SESSION["passwordError"];}?></p>
                </div>
                <p>Remembered password? <a href='/login'>Log in</a></p>
                <p>New here? <a href='/register'>Register for free</a></p>
        </form>
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
