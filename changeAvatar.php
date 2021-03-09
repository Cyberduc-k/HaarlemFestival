<?php

if (!isset($_SESSION)) session_start();

require_once ("models/User.php");
require_once ("services/UserService.php");
require_once ("validate.php");

// Handle form submission
$userService = new UserService();

if ($_POST) {
    // Clear any exiting error message
    unset($_SESSION["avatarError"]);

    if (
        isset($_SESSION["userId"]) &&
        isset($_SESSION["userType"]) &&
        !empty($_FILES["avatar"]["name"])
    ) {
        if ($userService->setAvatar((int)$_SESSION["userId"], $_FILES["avatar"])) {
            echo "<h2>Succesfully changed avatar</h2>";
        } else{
            header("Location: changeAvatar.php");
            exit;
        }
    } else {
        $_SESSION["avatarError"] = "Please select an image first";
        header("Location: changeAvatar.php");
        exit;
    }
}
//Just show the page
else {
    require_once ("menubar.php");

    if (isset($_SESSION["email"])) {
?>
        <html lang="en">
        <head>
            <title>Change Avatar</title>
            <link type="text/css" rel="stylesheet" href="css/style.css" />
            <script>
                // script to display image on change and to show a picker on click
                function triggerClick(e) {
                    document.querySelector('#profileImage').click();
                }

                function displayImage(e) {
                    if (e.files[0]) {
                        var reader = new FileReader();

                        reader.onload = e => {
                            document.querySelector('#profileDisplay').setAttribute('src', e.target.result);
                        };

                        reader.readAsDataURL(e.files[0]);
                    }
                }
            </script>
        </head>
        <body>
            <div class="content">
                <h1>Change Avatar</h1>
                <form action="changeAvatar.php" method="post" enctype="multipart/form-data">
                    <div class="formBody">
                        <div class="formField">
                            <img class='avatar' src="<?php echo $userService->getAvatarByEmail($_SESSION["email"])?>" onClick="triggerClick()" id="profileDisplay" alt="profile image">
                        </div>

                        <div class="formField">
                            <input type="file" alt="avatar" name="avatar" onChange="displayImage(this)" id="profileImage" accept="image/jpeg, image/png" style="display: none;">
                            <p>Click image to change</p>
                        </div>

                        <div>
                            <button type="submit" name="submit">Change avatar</button>
                        </div>

                        <div class="formField red">
                            <p><?php if(isset($_SESSION["avatarError"])){echo $_SESSION["avatarError"];}?></p>
                        </div>
                    </div>
                </form>
            </div>
        </body>
        </html>
<?php
    } else {
        echo "<h2>Something went wrong on our side, please contact the administrator.</h2>";
        echo "<h3>We weren't able to verify what user you where trying to edit, sorry!</h3>";
    }
}

?>
