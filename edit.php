<?php
if (!isset($_SESSION)) session_start();

require_once ("models/User.php");
require_once ("services/UserService.php");
require_once ("validate.php");

// Handle form submission
if ($_POST) {
    // Clear any exiting error message
    unset($_SESSION["editError"]);
    
    if (
        !empty($_POST["firstname"]) &&
        !empty($_POST["lastname"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["id"]) &&
        isset($_SESSION["userId"]) &&
        isset($_SESSION["userType"])
    ) {
        if (htmlentities($_POST["id"]) == $_SESSION["userId"] || (int)$_SESSION["userType"] >= UserTypes::ADMIN) {
            try {
                $updatedUser = new User();

                $updatedUser->setId(htmlentities($_POST["id"]));
                $updatedUser->setFirstname(htmlentities($_POST["firstname"]));
                $updatedUser->setLastname(htmlentities($_POST["lastname"]));
                $updatedUser->setEmail(htmlentities($_POST["email"]));

                // Check if users want to change password, of one field is empty that will be handled
                if (!empty($_POST["newPassword"]) || !empty($_POST["verifyPassword"])) {
                    if (htmlentities($_POST["newPassword"]) == htmlentities($_POST["verifyPassword"])) {
                        $updatedUser->setPassword(htmlentities($_POST["newPassword"]));
                    } else {
                        $_SESSION["editError"] = "Passwords do not match";
                        header("Location: edit.php");
                        exit;
                    }
                }

                $userService = new UserService();

                // Check if the edit should be immediate or if it should be confirmed first
                $isInstant = isset($_SESSION['userIdToEdit']);
                
                // Different feedback if the user was edit by an admin
                if ($isInstant && $userService->editUser($updatedUser, $isInstant)) {
                    echo "Succesfully edited user";
                } else if ($userService->editUser($updatedUser, $isInstant)) {
                    echo "Edit request was successfull. A confirmation mail was sent to confirm changes. <br>";
                    echo "If you edited your mail you will have recieved a confirmation mail in both the old and the new address";
                } else {
                    header("Location: edit.php");
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION["editError"] = "An error occured, try again please".$e;
                header("Location: edit.php");
                exit;
            }
        }
    } else {
        $_SESSION["editError"] = "Not all information was filled in";
        header("Location: edit.php");
        exit;
    }
}
// Just show the page
else {
    //type is own when the page is requested through the menu bar, so when the users wants to edit his own info
    if (isset($_GET["type"]) && (string)$_GET["type"] == "own")
        unset($_SESSION["userIdToEdit"]);

    $userService = new UserService();

    // If this variable is set the call came from a admin, so edit info of this user
    if (isset($_SESSION['userIdToEdit'])) {
        if (isset($_SESSION["userType"]) && (int)$_SESSION["userType"] >= UserTypes::ADMIN) {
            $userIdToEdit = (int)$_SESSION['userIdToEdit'];
            $user = $userService->getById($userIdToEdit);

            if (!is_null($user)) {
                // Only allow editing the page when the edited user is a user or is and admin and a superadmin is logged in
                if (
                    $user->getUsertype() == UserTypes::USER ||
                    ($user->getUsertype() == UserTypes::ADMIN && (int)$_SESSION["userType"] == UserTypes::SUPERADMIN)
                ) {
                    showEditPage($user);
                } else {
                    echo "<h2>You dont have sufficient permissions to edit this type of user</h2>";
                }
            } else {
                echo "<h2>We cannot find the user to edit, sorry!</h2>";
                unset($_SESSION["userIdToEdit"]);
            }
        } else {
            echo "<h2>You dont have sufficient permissions</h2>";
        }
    }
    // Var not set, so user is editing own profile
    else {
        if (isset($_SESSION["userId"])) {
            $userIdToEdit = (int)$_SESSION['userId'];
            $user = $userService->getById($userIdToEdit);

            if (!is_null($user)) {
                showEditPage($user);
            } else {
                echo "<h2>We cannot find the user to edit, sorry!</h2>";
            }
        } else {
            echo "<h2>Something went wrong on our side, please contact the administrator.</h2>";
            echo "<h3>We weren't able to verify what user you where trying to edit, sorry!</h3>";
        }
    }
}

// Show the edit page
function showEditPage(User $user) {
    require_once ("menubar.php");
?>
    <html lang="en">
    <head>
        <title>Edit Info</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div class="content">
            <h1>Edit User</h1>
            <form name="editForm" action="edit.php" method="post">
                <div class="formBody">
                    <div class="formField">
                        <input type="text" name="firstname" required placeholder="Firstname" value="<?php echo $user->getFirstname()?>"
                               pattern="^[a-zA-Z]{2,}$"/>
                    </div>

                    <div class="formField">
                        <input type="text" name="lastname" required placeholder="Lastname" value="<?php echo $user->getLastname()?>"
                               pattern="^[a-zA-Z]{2,}$"/>
                    </div>

                    <div class="formField">
                        <input type="email" id="emailField" name="email" required placeholder="Email" value="<?php echo $user->getEmail()?>"/>
                    </div>

                    <div class="formField">
                        <input type="password" name="newPassword" placeholder="New Password"
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                    </div>

                    <div class="formField">
                        <input type="password" name="verifyPassword" placeholder="Verify New Password"
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                    </div>

                    <input type="hidden" name="id" value="<?php echo $user->getId()?>">

                    <div>
                        <input id="submitBtn" type="submit" value="Edit Info" class="customButton"/>
                    </div>

                    <div class="formField red">
                        <p><?php if(isset($_SESSION["editError"])){echo $_SESSION["editError"];}?></p>
                    </div>
                </div>
            </form>
        </div>
    </body>
    </html>
<?php } ?>