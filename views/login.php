<html lang="en">
<head>
    <title>Login</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/login.css" />

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Only enable the login button when captcha is succesfully filled in. -->
    <!-- Another server-side check is performed that the captcha is ok to avoid hackers manually enabling the button -->
    <script type="text/javascript">
        function recaptchaCallback() {
            document.getElementById("submitBtn").disabled = false;
        }
    </script>
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <main id="loginForm">
        <h1>Login</h1>
        <form method="post">
            <div class="formBody">
                <div class="formField">
                    <input type="email" name="email" required placeholder="Email" />
                </div>

                <div class="formField">
                    <input type="password" name="password" required placeholder="Password" />
                </div>

                <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Lezlz0aAAAAAFwW9tFtLphdlXVJ6qJ5ut-WBXVn"></div>

                <div>
                    <input id="submitBtn" type="submit" value="Login" class="customButton" disabled/>
                </div>

                <div class="formField red">
                    <p><?php if(isset($_SESSION["loginError"])){echo $_SESSION["loginError"];}?></p>
                </div>
            </div>
            <div id='userNotes'>
                <p>Forgot password? <a href='/password_reset'>Reset it</a></p>
                <p>New here? <a href='/register'>Register for free</a></p>
            </div>
        </form>
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
