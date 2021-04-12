<html lang="en">
<head>
    <title>Change Avatar</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/changeAvatar.css" />
    <link type="text/css" rel="stylesheet" href="/css/innerNav.css" />
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
    <?php require __DIR__.'/menubar.php'; ?>

    <main>
        <nav>
            <ul>
                <li>
                    <a href='/programme'>Programme</a>
                </li>
                <li>
                    <a href='/user/edit'>Edit my information</a>
                </li>
                <li class="active">
                    <a href='/user/change_avatar'>Change Avatar</a>
                </li>
                <li>
                    <a href='/cart'>Cart</a>
                </li>
            </ul>
        </nav>
        <section class="content">
            <h1>Change Avatar</h1>
            <form method="post" enctype="multipart/form-data">
                <div class="formBody">
                    <div class="formField">
                        <img class='avatar' src="/<?php echo $image; ?>" onClick="triggerClick()" id="profileDisplay" alt="profile image">
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
        </section>
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
