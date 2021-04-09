<?php if (!isset($_SESSION)) session_start(); ?>

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
        <?php

            require_once("services/UserService.php");
            require_once("models/User.php");
            require_once("models/UserTypes.php");

            // save the username and password
            if ($_POST) {
                // Clear the error message
                unset($_SESSION["registerError"]);

                // First check if the captcha has been filled in
                $captcha = null;
                
                if (isset($_POST["g-recaptcha-response"])) {
                    $captcha = $_POST["g-recaptcha-response"];
                }

                if (!$captcha) {
                    $_SESSION["registerError"] = "Please complete the Captcha";
                    header("Location: register.php");
                    exit;
                }

                // Verify the captcha
                $secretKey = "6Lezlz0aAAAAAN4GQdN0V26BkQZHey2vKBCF1R8D";
                $ip = $_SERVER['REMOTE_ADDR'];
                // post request to server
                $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
                $response = file_get_contents($url);
                $responseKeys = json_decode($response,true);

                // Don't sign in when captcha failed
                if (!$responseKeys["success"]) {
                    $_SESSION["registerError"] = "Captcha incorrect, try again";
                    header("Location: register.php");
                    exit;
                }
                
                if (
                    !empty($_POST["firstname"]) &&
                    !empty($_POST["lastname"]) &&
                    !empty($_POST["email"]) &&
                    !empty($_POST["password"])
                ) {
                    $userService = new UserService();

                    // Check if there already is a user with this mail address
                    if ($userService->mailExists(htmlentities($_POST["email"]))) {
                        $_SESSION["registerError"] = "An user is already registered with this email address";
                        header("Location: register.php");
                        exit;
                    }

                    // Create a user with the specific variables
                    $user = new User();
                    $user->setFirstname(htmlentities($_POST["firstname"]));
                    $user->setLastname(htmlentities($_POST["lastname"]));
                    $user->setEmail(htmlentities($_POST["email"]));
                    $user->setPassword(htmlentities($_POST["password"]));
                    $user->setUsertype(UserTypes::CLIENT);

                    if ($userService->create($user)) {
                        echo "<div>Successful registration! <a href='index.php'>Login</a></div>";
                    } else {
                        echo "<div>Unable to register. <a href='register.php'>Please try again.</a></div>";
                    }
                } else {
                    $_SESSION["registerError"] = "Not all information was filled in";
                    header("Location: register.php");
                    exit;
                }
            }
            // show the registration form
            else {
        require_once("menubar.php");
?>
        <div id="loginForm">
            <h1>Registration Form</h1>
            <form action="register.php" method="post">
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
                        Already have an account? <a href='index.php'>Login</a>
                    </div>
                </div>
            </form>
            <?php } ?>
        </div>
    </body>
</html>
