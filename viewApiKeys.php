<?php if (!isset($_SESSION)) session_start();
require_once ("validate.php");
if ($_POST) {
    require_once ("services/ApiKeyService.php");
    require_once ("services/UserService.php");
    require_once ("models/ApiKey.php");

    // Clear any exiting error message
    unset($_SESSION["apiError"]);

    if (!empty($_POST["emailToAdd"])) {
        $apiKeyService = new ApiKeyService();
        $userService = new UserService();

        $email = (string)htmlentities($_POST["emailToAdd"]);

        if(empty($userService->getByEmail($email))){
            $_SESSION["apiError"] = "No user found with this email";
            header("Location: viewApiKeys.php");
            exit;
        }

        $key = new ApiKey();
        $key->setEmail($email);

        //Generate key and add it to db
        if($key->generate() && $apiKeyService->create($key)){
            header("Location: viewApiKeys.php");
        }
        else{
            $_SESSION["apiError"] = "Error occurred while creating key";
            header("Location: viewApiKeys.php");
            exit;
        }
    }
    else if(!empty($_POST["emailToDelete"])){
        $apiKeyService = new ApiKeyService();

        //Try to remove key
        if($apiKeyService->delete((string)htmlspecialchars($_POST["emailToDelete"]))){
            header("Location: viewApiKeys.php");
        }
        else{
            $_SESSION["apiError"] = "Error occurred while creating key";
            header("Location: viewApiKeys.php");
            exit;
        }
    }
    else {
        $_SESSION["apiError"] = "Not all information was filled in";
        header("Location: viewApiKeys.php");
        exit;
    }
}
else{
 ?>
    <html lang="en">
    <head>
        <title>Api Key List</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body>

    <?php
        require_once ("menubar.php");
        require_once ("models/UserTypes.php");
    ?>

    <div class="content">
        <h1>API Key List</h1>
        <table id="userList">
            <tr>
                <th>Email</th>
                <th>API key</th>
                <th>Controls</th>
            </tr>
            <?php

            require_once("services/ApiKeyService.php");
            require_once("models/ApiKey.php");

            $apiKeyService = new ApiKeyService();
            $keys = $apiKeyService->getAll();

            // Show table
            if (!is_null($keys) && !empty($keys)) {
                foreach ($keys as $key) {
                    ?>
                    <tr>
                        <td><?php echo $key->getEmail()?></td>
                        <td><?php echo $key->getApiKey()?></td>
                        <td>
                            <form class="" name="apiDeleteForm<?php echo $key->getEmail()?>" action="viewApiKeys.php" method="post">
                                <input type="hidden" name="emailToDelete" value="<?php echo $key->getEmail()?>"/>

                                <input class='tableBtn' type="submit" value="Delete"/>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            }
            else {
                echo "<h2>Sorry, we couldnt find any keys. Add a new one!</h2>";
            }
            ?>
        </table>
        <br>
        <br>
        <h2>Allow API access for user</h2>
        <form action="viewApiKeys.php" method="post">
            <div class="formBody">
                <div class="searchFormField">
                    <input type="email" name="emailToAdd" placeholder="Email" required/>
                </div>

                <div>
                    <input id="submitBtn" type="submit" value="Add" class="searchButton customButton"/>
                </div>

                <div class="formField red">
                    <p><?php if(isset($_SESSION["apiError"])){echo $_SESSION["apiError"];}?></p>
                </div>
            </div>
        </form>
    </div>
    </body>
    </html>
<?php
}?>