<html lang="en">
<head>
    <title>Api Key List</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/viewApiKeys.css" />
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <section class="content">
        <h1>API Key List</h1>
        <table>
            <tr>
                <th>Email</th>
                <th>API key</th>
                <th>Controls</th>
            </tr>
            <?php
                // Show table
                if (!is_null($keys) && !empty($keys)) {
                    foreach ($keys as $key) { ?>
                        <tr>
                            <td><?php echo $key->getEmail()?></td>
                            <td><?php echo $key->getApiKey()?></td>
                            <td>
                                <form class="tableButton" name="apiDeleteForm<?php echo $key->getEmail()?>" method="post">
                                    <input type="hidden" name="emailToDelete" value="<?php echo $key->getEmail()?>"/>

                                    <input class='tableBtn' type="submit" value="Delete"/>
                                </form>
                            </td>
                        </tr>
                    <?php }
                } else {
                    echo "<h2>Sorry, we couldnt find any keys. Add a new one!</h2>";
                }
            ?>
        </table>
        <br>
        <br>
        <h2>Allow API access for user</h2>
        <form id="allowAccessForm" method="post">
            <input id="emailInput" type="email" name="emailToAdd" placeholder="Email" required/>
            <input type="submit" value="Add" id="submit"/>

            <p><?php if(isset($_SESSION["apiError"])){echo $_SESSION["apiError"];}?></p>
        </form>
    </section>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
