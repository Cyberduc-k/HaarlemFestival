<?php

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
        header("Location: /login");
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
        header("Location: /login");
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
            header("Location: /");
        } else {
            header("Location: /login");
            exit;
        }
    } else {
        echo "Not all data was filled in";
    }
} else {
    require __DIR__.'/../views/login.php';
}

?>
