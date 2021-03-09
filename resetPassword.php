<?php if (!isset($_SESSION)) session_start(); ?>

<html lang="en">
<head>
    <title>Reset Password</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
</head>
<body>
<div id="resetPasswordForm">
    <?php

    // form is submitted, check if access will be granted
    if ($_POST) {
        if (!empty($_POST["email"])) {
            require_once("services/PasswordService.php");
            require_once("services/UserService.php");
            require_once("services/MailService.php");
            require_once("models/ResetKey.php");
            require_once("models/User.php");

            unset($_SESSION["passwordError"]);

            $passwordService = new PasswordService();
            $userService = new UserService();

            $user = $userService->getByEmail(htmlentities($_POST["email"]));

            if (!is_null($user)) {
                $resetKey = new ResetKey();
                
                // fill key and add it to db
                if ($resetKey->fillForId($user->getId()) && $passwordService->create($resetKey)) {
                    $subject = "Password reset request";
                    $body = "Copy the following link to reset password: <br>".$resetKey->getLink();

                    if (MailService::getInstance()->sendMail($user->getEmail(), $subject, $body)) {
                        echo "Check your inbox for a confirmation mail.";
                    } else {
                        $_SESSION["passwordError"] = "Sending the mail failed, sorry";
                        header("Location: resetPassword.php");
                        exit;
                    }
                    
                    unset($_SESSION["passwordError"]);
                } else {
                    $_SESSION["passwordError"] = "creating reset key failed, sorry";
                    header("Location: resetPassword.php");
                    exit;
                }
            } else {
                $_SESSION["passwordError"] = "No user found with this email address";
                header("Location: resetPassword.php");
                exit;
            }
        } else {
            $_SESSION["passwordError"] = "Please enter an email address";
            header("Location: resetPassword.php");
            exit;
        }
    }
    // show the reset password form
    else {
?>
        <div class="content">
            <form action="resetPassword.php" method="post">
                <div id="formHeader">Reset Password</div>
                <div class="formBody">
                    <div class="formField">
                        <input type="email" name="email" required placeholder="Email" />
                    </div>
                    <div>
                        <input id="submitBtn" type="submit" value="Reset Password" class="customButton"/>
                    </div>
                    <div class="formField red">
                        <p><?php if(isset($_SESSION["passwordError"])){echo $_SESSION["passwordError"];}?></p>
                    </div>
                </div>
                <div id='userNotes'>
                    <p>Remembered password? <a href='index.php'>Log in</a></p>
                    <p>New here? <a href='register.php'>Register for free</a></p>
                </div>
            </form>
        </div>
    <?php } ?>
</div>
</body>
</html>