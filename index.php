<?php
require_once ("showErrors.php");

if (!isset($_SESSION)) session_start();

// form is submitted, check if access will be granted
if ($_POST) {
    unset($_SESSION["loginError"]);

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
                        <input id="submitBtn" type="submit" value="Login" class="customButton"/>
                    </div>

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