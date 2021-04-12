<html lang="en">
<head>
    <title>Register</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/login.css" />
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
        <h1>Registration Form</h1>
        <form method="post">
            <div class="formBody">
                <div class="formField">
                    <input type="text" name="firstname" required placeholder="Firstname"
                           pattern="^[a-zA-Z ]{2,}$"/>
                </div>

                <div class="formField">
                    <input type="text" name="lastname" required placeholder="Lastname"
                           pattern="^[a-zA-Z ]{2,}$"/>
                </div>

                <div class="formField">
                    <input type="email" name="email" required placeholder="Email" />
                </div>

                <div class="formField">
                    <input type="password" name="password" required placeholder="Password"
                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                </div>

                <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Lezlz0aAAAAAFwW9tFtLphdlXVJ6qJ5ut-WBXVn"></div>
                <div class="formField red">
                    <p><?php if(isset($_SESSION["registerError"])){echo $_SESSION["registerError"];}?></p>
                </div>

                <div>
                    <input id="submitBtn" type="submit" value="Register" class="customButton" disabled />
                </div>
                <div id='userNotes'>
                    Already have an account? <a href='/login'>Login</a>
                </div>
            </div>
        </form>
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
