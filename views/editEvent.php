<!DOCTYPE html>
<html lang="end">
<head>
    <title>Edit <?php echo $eventName; ?></title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/editEventPage.css" />
    <script src="https://cdn.tiny.cloud/1/dr4sffq9mze32bw2u01wp5edapqoq9qsjlrp2egutz2i8bvw/tinymce/5/tinymce.min.js"
            referrerpolicy="origin"></script>
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <main>
        <section class="content">
            <header>
                <h1><?php echo $eventName; ?></h1>
            </header>

            <article>
                <textarea id="mytextarea" name="mytextarea"></textarea>
                <p>
                    <button id="saveBtn" onclick="saveContent()">Save</button>
                </p>

                <form action="uploadIMG.php?contentId=<?php echo $content->getId(); ?>&event=<?php echo $name; ?>" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <label>Select files to upload:</label>
                        <p>
                            <input name="file[]" type="file" multiple />
                            <input type="submit" value="Upload" />
                        </p>
                    </fieldset>
                </form>
            </article>
            <article>
                <h2>Select an image to use as header</h2>
                <form method="post">
                    <?php foreach ($images as $image) { ?>
                        <div>
                            <img src="uploads/uploadedIMG/<?php echo $image->getId().'-'.$image->getName(); ?>" />
                            <input type="radio" name="img" value="<?php echo $image->getId(); ?>" />
                        </div>
                    <?php } ?>
                    <input type="submit" value="Select" />
                </form>
            </article>
        </section>
    </main>

    <script>
        tinymce.init({
            selector: '#mytextarea',
            plugins: 'a11ychecker casechange linkchecker autolink lists checklist media mediaembed pageembed ' +
                'permanentpen powerpaste table advtable tinymcespellchecker',
            toolbar: 'undo redo bullist numlist table',
            toolbar_mode: 'floating',
            setup: function (editor) {
                editor.on('init', function (e) {
                    editor.setContent(`<?php echo $content->getText(); ?>`);
                });
            }
        });

        function saveContent() {
            const content = tinymce.get("mytextarea").getContent();
            const eventID = <?php echo $event->getId(); ?>;
            const body = new FormData();

            body.append("var", content);
            body.append("eventID", eventID);

            fetch("/updatePageContent.php", {
                method: "POST",
                body,
            }).then(data => {
                console.log(data);
            });
        }
    </script>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
