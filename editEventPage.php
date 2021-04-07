<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once ("menubar.php");
    require_once ("services/EventService.php");

    $es = new EventService();

    if (isset($_GET["event"]))
    {
        $eventID = $_GET["event"];
        $event = $es->getById($eventID);
    }
    else{
        header("Location: home.php");
    }

    $eventName = ucfirst($event->getName());

    echo "<title>$eventName</title>"
    ?>
    <title>Home</title>
    <script src="https://cdn.tiny.cloud/1/dr4sffq9mze32bw2u01wp5edapqoq9qsjlrp2egutz2i8bvw/tinymce/5/tinymce.min.js"
            referrerpolicy="origin"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/editEventPage.css" />
</head>
<body>

<script>
    function hideAbout() {
        var x = document.getElementById("about");
        var y = document.getElementById("schedule");

        x.style.display = "none";
        y.style.display = "block";
    }

    function hideSchedule() {
        var x = document.getElementById("schedule");
        var y = document.getElementById("about");

        x.style.display = "none";
        y.style.display = "block";
    }
</script>

<section id="about">
    <?php
    require_once "retreiveContent.php";
    require_once "services/ContentService.php";

    echo "<header>
    <h1>$eventName</h1>
        </header>";

    // content halen uit db
        $rc = new retrieveContent();
        $content =  $rc->retrieve($eventID);

    function getAboutContent($content){

        return $content->getText();
    }
    ?>
    <script>

        tinymce.init({
            selector: '#mytextarea',
            plugins: 'a11ychecker casechange linkchecker autolink lists checklist media mediaembed pageembed ' +
                'permanentpen powerpaste table advtable tinymcespellchecker',
            toolbar: 'undo redo bullist numlist table',
            toolbar_mode: 'floating',
            setup: function (editor) {
                editor.on('init', function (e) {
                    editor.setContent(getAboutContent());
                });
            }
        });

        function getAboutContent() {
            // content ophalen via php
            var content = `<?php echo getAboutContent($content); ?>`;

            return content;
        }

    </script>
    <article>
        <header>
            <?php
            echo "<h3>$eventName</h3>"
            ?>
        </header>
        <textarea id="mytextarea" name="mytextarea">
            </textarea>
        <p>
            <button id="saveBtn" onclick="saveContent()" id="savebtn">Save</button>
        </p>
        <script>
            function saveContent() {
                // edited content opslaan in variable.
                var myContent = tinymce.get("mytextarea").getContent();
                var eventID = `<?php echo $eventID ?>`;

                // edited content doorsturen naar php
                $.ajax({
                    url: 'updatePageContent.php',
                    type: 'POST',
                    data: {'var' : myContent,
                        'eventID' : eventID},
                    success: function(data) {
                        console.log(data); // Inspect this in your console
                    }
                })
            }
        </script>
        <form action="uploadIMG.php?contentId=<?php echo $content->getId(); ?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <p>
                    Select File to Upload:
                </p>
                <p>
                    <input name="file[]" type="file" multiple>
                    <input type="submit" name="submit" value="Upload" required>
                </p>
            </fieldset>
        </form>
    </article>
</section>
</body>
</html>

