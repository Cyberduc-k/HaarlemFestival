<html lang="en">
<head>
    <title>User List</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/viewUsers.css" />
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <section class="content">
        <h1>User List</h1>
        <table id="userList">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>Register date</th>
                    <th>Usertype</th>
                    <th>Controls</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo $user->getId()?></td>
                        <td><?php echo $user->getFirstname()?></td>
                        <td><?php echo $user->getLastname()?></td>
                        <td><?php echo $user->getEmail()?></td>
                        <td><?php echo $user->getRegisterDateString()?></td>
                        <td><?php echo UserTypes::getType($user->getUserType())?></td>
                        <td>
                            <?php
                                //Show edit/login/delete based on what usertype is logged in
                                $loggedInUserType = (int)$_SESSION["userType"];
                                $shownUserType = $user->getUserType();

                                if ($loggedInUserType > $shownUserType) {
                                    ?>
                                    <a class="tableBtn" href="/user/<?php echo $user->getId(); ?>/edit">Edit</a>
                                    <form class="tableForm" action="loginForUser.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $user->getId()?>"/>
                                        <input class='tableBtn' type="submit" value="Login"/>
                                    </form>
                                    <?php if ($loggedInUserType == UserTypes::SUPERADMIN) { ?>
                                        <form class="tableForm" action="deleteUser.php" method="post">
                                            <input type="hidden" name="id" value="<?php echo $user->getId()?>"/>
                                            <input class='tableBtn' type="submit" value="Delete"/>
                                        </form>
                                    <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <form id="searchForm" method="post">
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
    </section>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
