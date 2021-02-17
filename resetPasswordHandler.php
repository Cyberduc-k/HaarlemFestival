<?php if(!isset($_SESSION)) session_start(); ?>
<html lang="en">
<head>
    <title>Reset Password</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
</head>
<body>
    <div id="resetPasswordForm">

        <?php
            require_once("services/PasswordService.php");
            require_once("services/UserService.php");
            require_once("models/ResetKey.php");
            require_once("models/User.php");

            //Form has been filled in
            if($_POST){
                unset($_SESSION["createPasswordError"]);
                //Session vars are secure, so no need to check a key again
                if(!empty($_SESSION["idToResetPassword"]) &&
                    !empty($_POST["newPassword"]) &&
                    !empty($_POST["verifyPassword"]))
                {
                    $userService = new UserService();

                    //Change the password
                    if($userService->changePassword((int)htmlentities($_SESSION["idToResetPassword"]),
                        (string)htmlentities($_POST["newPassword"]),
                        (string)htmlentities($_POST["verifyPassword"])))
                    {
                        echo "Password changed! <a href='index.php'>Login</a>";
                    }
                    else{
                        echo "Something went wrong here";
                    }
                }
                else {
                    if(!empty($_POST["resetLink"]))
                        echo "Something went wrong, <a href='".htmlentities((string) $_POST["resetLink"])."> Try again </a>";
                    else
                        echo "Something went wrong, please use your link again";
                }
            }
            //Just show the page
            else{
                if(!empty($_GET["userId"]) &&
                    !empty($_GET["key"])){
                    //Get the ResetKey for this user from the database
                    $passwordService = new PasswordService();
                    $resetKey = $passwordService->getById((int)htmlentities($_GET["userId"]));

                    //If a key exists
                    if(!is_null($resetKey)){
                        //If it is not expired and matches
                        if($resetKey->getExpDate()->getTimestamp() > time()){
                            if($resetKey->getKey() == (string) htmlentities($_GET["key"])){
                                //Store the id of the users password we are trying to edit
                                $_SESSION["resetLink"] = $resetKey->getLink();
                                $_SESSION["idToResetPassword"] = $resetKey->getUserId();
                                ?>
                                <div class="content">
                                    <form action="resetPasswordHandler.php" method="post">

                                        <div id="formHeader">New Password</div>

                                        <div class="formBody">
                                            <div class="formField">
                                                <input type="password" name="newPassword" required placeholder="New Password"
                                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                                            </div>
                                            <div class="formField">
                                                <input type="password" name="verifyPassword" required placeholder="Verify New Password"
                                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                                            </div>
                                            <div>
                                                <input id="submitBtn" type="submit" value="Create Password" class="customButton"/>
                                            </div>

                                            <div class="formField red">
                                                <p><?php if(isset($_SESSION["createPasswordError"])){echo $_SESSION["createPasswordError"];}?></p>
                                            </div>
                                        </div>
                                        <div id='userNotes'>
                                            <p>Remembered password? <a href='index.php'>Log in</a></p>
                                            <p>New here? <a href='register.php'>Register for free</a></p>
                                        </div>
                                    </form>
                                </div>
                                <?php
                            }
                            else{
                                echo "Your key is invalid, did you copy the entire link?";
                            }
                        }
                        else{
                            echo "Your link has expired, request a new one";
                        }
                    }
                    else{
                        echo "We cannot find the proper link for this user in our system, can you request a new one?";
                    }
                }
                else{
                    echo "Your link is invalid, did you copy the entire link?";
                }
            }
        ?>
    </div>
</body>
</html>
