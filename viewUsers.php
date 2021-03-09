<?php if (!isset($_SESSION)) session_start(); ?>

<html lang="en">
    <head>
        <title>User List</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body>
    
    <?php
    
    require_once ("validate.php");
    require_once ("menubar.php");
    require_once ("models/UserTypes.php");
    
    ?>
    
    <div class="content">
        <h1>User List</h1>
        <table id="userList">
            <tr>
                <th>#</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Register date</th>
                <th>Usertype</th>
                <th>Controls</th>
            </tr>
            <?php
            
            require_once("services/UserService.php");
            require_once("models/User.php");

            const VALID_KEYS = array("firstname", "lastname", "email", "registerDate");

            $userService = new UserService();
            $users = array();

            // Fill the page with filtered users
            if (
                !empty($_POST["firstname"]) || !empty($_POST["lastname"])
                || !empty($_POST["email"]) || !empty($_POST["registerDate"])
            ) {
                $args = array();
                
                // Make sure there arent any malicious post variables
                foreach ($_POST as $key => $value) {
                    if (in_array($key, VALID_KEYS))
                        $args[htmlentities((string)$key)] = htmlentities((string)$value);
                }

                $users = $userService->getWithArgs($args);
            }
            // Or just with all users
            else {
                $users = $userService->getAll();
            }

            // Show table
            if (!is_null($users) && !empty($users)) {
                foreach ($users as $user) {
                    ?>
                    <tr>
                        <td><?php echo $user["id"]?></td>
                        <td><?php echo $user["firstname"]?></td>
                        <td><?php echo $user["lastname"]?></td>
                        <td><?php echo $user["email"]?></td>
                        <td><?php echo $user["register_date"]?></td>
                        <td><?php echo UserTypes::getType((int)$user["usertype"])?></td>
                        <td>
                            <?php
                            //Show edit/login/delete based on what usertype is logged in
                            $loggedInUserType = (int)$_SESSION["userType"];
                            $shownUserType = (int)$user["usertype"];
                            
                            if ($shownUserType == UserTypes::USER ||
                                ($shownUserType == UserTypes::ADMIN && $loggedInUserType == UserTypes::SUPERADMIN)
                            ) {
                                ?>
                                <form class="tableForm" name="tableEditForm<?php echo $user["id"]?>" action="editOtherUser.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $user["id"]?>"/>

                                    <input class='tableBtn' type="submit" value="Edit"/>
                                </form>
                                <form class="tableForm" name="tableLoginForm<?php echo $user["id"]?>" action="loginForUser.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $user["id"]?>"/>

                                    <input class='tableBtn' type="submit" value="Login"/>
                                </form>
                                <?php
                                if ($loggedInUserType == UserTypes::SUPERADMIN) {
                                    ?>
                                    <form class="tableForm" name="tableDeleteForm<?php echo $user["id"]?>" action="deleteUser.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $user["id"]?>"/>

                                        <input class='tableBtn' type="submit" value="Delete"/>
                                    </form>
                                <?php
                                }
                            }
                            ?></td>
                    </tr>
                    <?php
                }
            }
            else {
                echo "<h2>Sorry, we couldnt find any users</h2>";
            }
            
            ?>
        </table>
        <form action="viewUsers.php" method="post">
            <div class="formBody">
                <div class="searchFormField">
                    <input type="text" name="firstname" placeholder="Firstname" />
                </div>

                <div class="searchFormField">
                    <input type="text" name="lastname" placeholder="Lastname" />
                </div>

                <div class="searchFormField">
                    <input type="email" name="email" placeholder="Email" />
                </div>

                <div class="searchFormField">
                    <input type="date" name="registerDate" placeholder="Registration Date" />
                </div>

                <div>
                    <input id="submitBtn" type="submit" value="Search" class="searchButton customButton"/>
                </div>
            </div>
        </form>
    </div>
    </body>
</html>