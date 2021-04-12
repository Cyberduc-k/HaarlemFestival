<!DOCTYPE html>
<hmtl lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"   content="width=device-width">
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/addPage.css" />
    <title>Add or Delete Page</title>
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <main>
        <form id="addForm" method="post">
            <fieldset>
                <h2>Add event page</h2>
                <div class="container">
                    <p>
                        <label id="eventNameLabel">Event Name <input id="eventNameInput" type="text" name="name" required/></label>
                    </p>
                    <p>
                        <label>Colour <input type="text" name="colour" required/></label>
                    </p>
                    <p>
                        <label>Header <input type="text" name="header" required/></label>
                    </p>
                    <p>
                        <label>Text <textarea id=textForm name="text" required></textarea></label>
                    </p>
                </div>
                <input type="submit" value="Add event page"/>
            </fieldset>
        </form>
        <form id="deleteForm" method="POST" >
            <fieldset>
                <h2>Delete Event page</h2>
                <label for="Event"> Event : </label>
                <select id="cmbEvent" name="Event"
                        onchange="document.getElementById('selected_text').value=this.options[this.selectedIndex].text">
                    <option value="0">Select Event</option>
                    <?php
                        foreach ($events as $event) {
                            $id = $event->getId();
                            $name = $event->getName();

                            echo "<option value=$id>$name</option>";
                        }
                    ?>
                </select>

                <input type="hidden" name="selected_text" id="selected_text" value="" />
                <input type="submit" name="delete" value="Delete"/>
            </fieldset>
        </form>
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</hmtl>
