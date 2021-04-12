<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/UserService.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/UserTypes.php';

function run(?int $id) {
    if (!isset($_SESSION['userId'])) {
        header("Location: /login");
        return;
    }

    $userService = new UserService();

    // handle form submission
    if ($_POST) {
        // clear any existing error message
        unset($_SESSION['editError']);

        if (
            !empty($_POST['firstname']) &&
            !empty($_POST['lastname']) &&
            !empty($_POST['email']) &&
            !empty($_POST['id'])
        ) {
            if ((int)htmlentities($_POST['id']) == $_SESSION['userId'] || $_SESSION['userType'] >= UserTypes::VOLUNTEER) {
                try {
                    $updatedUser = new User();
                    
                    $updatedUser->setId((int)htmlentities($_POST['id']));
                    $updatedUser->setFirstname(htmlentities($_POST['firstname']));
                    $updatedUser->setLastname(htmlentities($_POST['lastname']));
                    $updatedUser->setEmail(htmlentities($_POST['email']));

                    if (!empty($_POST['newPassword']) || !empty($_POST['verifyPassword'])) {
                        if (htmlentities($_POST['newPassword']) == htmlentities($_POST['verifyPassword'])) {
                            $updatedUser->setPassword(htmlentities($_POST['newPassword']));
                        } else {
                            $_SESSION['editError'] = "Passwords do not match";
                            header("Location: ".$_SERVER['REQUEST_URI']);
                            return;
                        }
                    }

                    $isInstant = isset($id);

                    if ($isInstant && $userService->editUser($updatedUser, $isInstant)) {
                        echo "Succesfully edited user";
                    } else if ($userService->editUser($updatedUser, $isInstant)) {
                        echo "Edit request was successfull. A confirmation mail was sent to confirm changes. <br>";
                        echo "If you edited your mail you will have recieved a confirmation mail in both the old and the new address";
                    } else {
                        header("Location: ".$_SERVER['REQUEST_URI']);
                        return;
                    }
                } catch (Exception $e) {
                    $_SESSION['editError'] = "An error occured, please try again";
                    header("Location: ".$_SERVER['REQUEST_URI']);
                    return;
                }
            } else {
                echo '<h2>You do not have sufficient permissions</h2>';
                return;
            }
        } else {
            $_SESSION['editError'] = "Not all information was filled in";
            header("Location: ".$_SERVER['REQUEST_URI']);
            return;
        }
    } else {
        if (isset($id) && $id != $_SESSION['userId']) {
            $user = $userService->getById($id);

            if (!is_null($user)) {
                if ($user->getUsertype() >= $_SESSION['userType']) {
                    echo '<h2>You do not have sufficient permissions</h2>';
                    return;
                }
            } else {
                echo '<h2>Could not find that user</h2>';
                return;
            }
        } else {
            $user = $userService->getById($_SESSION['userId']);
        }

        require __DIR__.'/../views/editUser.php';
    }
}

?>
