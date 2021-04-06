<html lang="en">
<head>
    <title>Home</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
require_once ("menubar.php");
?>

<header>
    <h1>Food in Haarlem</h1>
</header>

<nav>
    <ul>
        <li>
            <a onclick="hideRestaurants()">
                About
            </a>
        </li>
        <li>
            <a onclick="hideAbout()">
                Restaurants
            </a>
        </li>
        <li>
            <a href="home.php">
                Reservations
            </a>
        </li>
    </ul>
</nav>

<script>
    function hideAbout() {
        var x = document.getElementById("about");
        var y = document.getElementById("restaurants");

        x.style.display = "none";
        y.style.display = "block";
    }

    function hideRestaurants() {
        var x = document.getElementById("restaurants");
        var y = document.getElementById("about");

        x.style.display = "none";
        y.style.display = "block";
    }
</script>

<section id="about" class="foodContent">
    <article>
        <header>
            <h3>About test</h3>
        </header>

        <p>
            blablabla weewoo. I am mr tekst, here for testing purposes.
        </p>

    </article>
</section>
<section id="restaurants" class="foodContent">
    <article>
        <header>
            <h3>
                Restaurants
            </h3>
        </header>

        <p>
        Upload picture:

        <form action="uploadIMG.php" method="post" enctype="multipart/form-data">
            Select File to Upload:
            <input name="file" type="file" multiple>
            <input name="contentPageId" type="text" required>
            <input type="submit" name="submit" value="Upload" required>
        </form>
        </p>

    </article>
</section>
</body>

</html>
