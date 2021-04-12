<html lang="en">
<head>
    <title>Reset Password</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <main id="resetPasswordForm">
        <?php
            if (!empty($_GET["userId"]) && !empty($_GET["key"])) {
                // Get the ResetKey for this user from the database
                $passwordService = new PasswordService();
                $resetKey = $passwordService->getById((int)htmlentities($_GET["userId"]));

                // If a key exists
                if (!is_null($resetKey)) {
                    // If it is not expired and matches
                    if ($resetKey->getExpDate()->getTimestamp() > time()) {
                        if ($resetKey->getKey() == (string) htmlentities($_GET["key"])) {
                            // Store the id of the users password we are trying to edit
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
                        } else {
                            echo "Your key is invalid, did you copy the entire link?";
                        }
                    } else {
                        echo "Your link has expired, request a new one";
                    }
                } else {
                    echo "We cannot find the proper link for this user in our system, can you request a new one?";
                }
            } else {
                echo "Your link is invalid, did you copy the entire link?";
            }
        ?>
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
