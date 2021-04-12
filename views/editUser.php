<html lang="en">
<head>
    <title>Edit Info</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/editUser.css" />
    <link type="text/css" rel="stylesheet" href="/css/innerNav.css" />
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <main>
        <?php if (is_null($id)) { ?>
            <nav>
                <ul>
                    <li>
                        <a href='/programme'>Programme</a>
                    </li>
                    <li class="active">
                        <a href='/user/edit'>Edit my information</a>
                    </li>
                    <li>
                        <a href='/user/change_avatar'>Change Avatar</a>
                    </li>
                    <li>
                        <a href="/cart">Cart</a>
                    </li>
                </ul>
            </nav>
        <?php } ?>

        <section class="content">
            <h1>Edit User</h1>
            <form name="editForm" method="post">
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
        </section>
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
