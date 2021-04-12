<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/UserService.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/UserTypes.php';

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
        header("Location: /register");
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
        header("Location: /register");
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
            header("Location: /register");
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
        header("Location: /register");
        exit;
    }
} else {
    require __DIR__.'/../views/register.php';
}

?>
