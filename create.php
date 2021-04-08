<?php if(!isset($_SESSION)) session_start(); ?>

<html lang="en">
<head>
    <title>Add new user</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/createUser.css" />
</head>
<body>

<?php

require_once ("models/User.php");
require_once ("services/UserService.php");
require_once ("validate.php");
require_once ("menubar.php");

// Handle form submission
if ($_POST) {
    // Clear any exiting error message
    unset($_SESSION["createError"]);

    if (
        !empty($_POST["firstname"]) &&
        !empty($_POST["lastname"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["password"]) &&
        !empty($_POST["usertype"]) &&
        isset($_SESSION["userType"])
    ) {
        // Make sure the logged in user is a superadmin
        if ((int)$_SESSION["userType"] == UserTypes::SUPERADMIN) {
            try {
                // Create the new user
                $user = new User();

                $user->setFirstname((string)htmlentities($_POST["firstname"]));
                $user->setLastname((string)htmlentities($_POST["lastname"]));
                $user->setEmail((string)htmlentities($_POST["email"]));
                $user->setPassword((string)htmlentities($_POST["password"]));
                $user->setUsertype((int)htmlentities($_POST["usertype"]));

                $userService = new UserService();

                if ($userService->create($user)) {
                    echo "Succesfully created user";
                } else {
                    header("Location: create.php");
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION["createError"] = "An error occured, try again please".$e;
                header("Location: create.php");
                exit;
            }
        }
    } else {
        $_SESSION["editError"] = "Not all information was filled in";
        header("Location: create.php");
        exit;
    }
} else {
?>
    <div class="content">
        <h1>Add new user</h1>
        <form name="editForm" action="create.php" method="post">
            <div class="formBody">
                <div class="formField">
                    <input type="text" name="firstname" required placeholder="Firstname"
                           pattern="^[a-zA-Z]{2,}$"/>
                </div>

                <div class="formField">
                    <input type="text" name="lastname" required placeholder="Lastname"
                           pattern="^[a-zA-Z]{2,}$"/>
                </div>

                <div class="formField">
                    <input type="email" id="emailField" name="email" required placeholder="Email"/>
                </div>

                <div class="formField">
                    <input type="password" name="password" placeholder="Password"
                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                </div>

                <input type="radio" id="male" name="usertype" value="0">
                <label for="0">User</label><br>
                <input type="radio" id="female" name="usertype" value="1">
                <label for="1">Admin</label><br>
                <input type="radio" id="other" name="usertype" value="2">
                <label for="2">Superadmin</label>

                <div>
                    <input id="submitBtn" type="submit" value="Create" class="customButton"/>
                </div>

                <div class="formField red">
                    <p><?php if(isset($_SESSION["createError"])){echo $_SESSION["createError"];}?></p>
                </div>
            </div>
        </form>
    </div>
<?php } ?>
</body>
</html>
