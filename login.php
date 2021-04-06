<?php
require_once ("showErrors.php");

if (!isset($_SESSION)) session_start();

// form is submitted, check if access will be granted
if ($_POST) {
    unset($_SESSION["loginError"]);

    // First check if the captcha has been filled in
    $captcha = null;
    if (isset($_POST["g-recaptcha-response"])) {
        $captcha = $_POST["g-recaptcha-response"];
    }

    if (!$captcha) {
        $_SESSION["loginError"] = "Please complete the Captcha";
        header("Location: index.php");
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
        $_SESSION["loginError"] = "Captcha incorrect, try again";
        header("Location: index.php");
        exit;
    }

    //Login with supplied credentials
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        require_once("services/UserService.php");
        require_once("models/User.php");

        $userService = new UserService();
        $valid = $userService->validateLogin(htmlentities($_POST["email"]), htmlentities($_POST["password"]));

        // Set all session variables used to track if a user is logged in
        if ($valid) {
            $user = $userService->getByEmail(htmlentities($_POST["email"]));

            $_SESSION["firstname"] = $user->getFirstname();
            $_SESSION["userId"] = $user->getId();
            $_SESSION["userType"] = $user->getUsertype();
            $_SESSION["email"] = $user->getEmail();
            header("Location: home.php");
        } else {
            header("Location: index.php");
            exit;
        }
    } else {
        echo "Not all data was filled in";
    }
}
// show the login form
else {
?>

<html lang="en">
    <head>
        <title>Login</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />

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
        <div id="loginForm">
            <form action="index.php" method="post">

                <div id="formHeader">Login</div>

                <div class="formBody">
                    <div class="formField">
                        <input type="email" name="email" required placeholder="Email" />
                    </div>

                    <div class="formField">
                        <input type="password" name="password" required placeholder="Password" />
                    </div>

                    <div>
                        <input id="submitBtn" type="submit" value="Login" class="customButton" disabled/>
                    </div>

                    <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Lezlz0aAAAAAFwW9tFtLphdlXVJ6qJ5ut-WBXVn"></div>

                    <div class="formField red">
                        <p><?php if(isset($_SESSION["loginError"])){echo $_SESSION["loginError"];}?></p>
                    </div>
                </div>
                <div id='userNotes'>
                    <p>Forgot password? <a href='resetPassword.php'>Reset it</a></p>
                    <p>New here? <a href='register.php'>Register for free</a></p>
                </div>
            </form>
            <?php } ?>
        </div>
    </body>
</html>