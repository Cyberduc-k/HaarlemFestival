<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/ApiKeyService.php';
require_once __DIR__.'/../services/UserService.php';
require_once __DIR__.'/../models/ApiKey.php';
require_once __DIR__.'/../models/UserTypes.php';

if (!isset($_SESSION['userType'])) {
    header("Location: /login");
    exit;
}

if ($_SESSION['userType'] != UserTypes::SUPERADMIN) {
    echo "<h1>You do not have permission to access this page";
    exit;
}

$apiKeyService = new ApiKeyService();

if ($_POST) {
    // Clear any exiting error message
    unset($_SESSION["apiError"]);

    if (!empty($_POST["emailToAdd"])) {
        $userService = new UserService();
        $email = (string)htmlentities($_POST["emailToAdd"]);

        if (empty($userService->getByEmail($email))) {
            $_SESSION["apiError"] = "No user found with this email";
            header("Location: /api/keys");
            exit;
        }

        $key = new ApiKey();
        $key->setEmail($email);

        // Generate key and add it to db
        if ($key->generate() && $apiKeyService->create($key)) {
            header("Location: /api/keys");
        } else {
            $_SESSION["apiError"] = "Error occurred while creating key";
            header("Location: /api/keys");
            exit;
        }
    } else if (!empty($_POST["emailToDelete"])) {
        // Try to remove key
        if ($apiKeyService->delete((string)htmlspecialchars($_POST["emailToDelete"]))) {
            header("Location: /api/keys");
        } else {
            $_SESSION["apiError"] = "Error occurred while creating key";
            header("Location: /api/keys");
            exit;
        }
    } else {
        $_SESSION["apiError"] = "Not all information was filled in";
        header("Location: /api/keys");
        exit;
    }
} else {
    $keys = $apiKeyService->getAll();

    require __DIR__.'/../views/apiKeys.php';
}
