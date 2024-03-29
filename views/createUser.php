<html lang="en">
<head>
    <title>Add new user</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/createUser.css" />
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <main class="content">
        <h1>Add new user</h1>
        <form name="editForm" method="post">
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
                <label for="0">Client</label><br>
                <input type="radio" id="female" name="usertype" value="1">
                <label for="1">Volunteer</label><br>
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
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
