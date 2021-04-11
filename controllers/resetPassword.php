<?php

if (!isset($_SESSION)) session_start();

// form is submitted, check if access will be granted
if ($_POST) {
    if (!empty($_POST["email"])) {
        require_once __DIR__.'/../services/PasswordService.php';
        require_once __DIR__.'/../services/UserService.php';
        require_once __DIR__.'/../services/MailService.php';
        require_once __DIR__.'/../models/ResetKey.php';
        require_once __DIR__.'/../models/User.php';

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
                    header("Location: /password_reset");
                    exit;
                }
                
                unset($_SESSION["passwordError"]);
            } else {
                $_SESSION["passwordError"] = "creating reset key failed, sorry";
                header("Location: /password_reset");
                exit;
            }
        } else {
            $_SESSION["passwordError"] = "No user found with this email address";
            header("Location: /password_reset");
            exit;
        }
    } else {
        $_SESSION["passwordError"] = "Please enter an email address";
        header("Location: /password_reset");
        exit;
    }
} else {
    require __DIR__.'/../views/resetPassword.php';
}
