<html lang="en">
<head>
    <title>Home</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
</head>
<body>
<nav>
    <ul>
        <li>
            <a href="home.php">
                Home
            </a>
        </li>
        <li>
            <a href="home.php">
                Dance
            </a>
        </li>
        <li>
            <a href="foodPage.php">
                Food
            </a>
        </li>
        <li>
            <a href="historicPage.php">
                Historic
            </a>
        </li>
        <li>
            <a href="jazzPage.php">
                Jazz
            </a>
        </li>
        <li>
            <a href="home.php">
                Contact
            </a>
        </li>
        <li>
            <a href="index.php">
                Log in
            </a>
        </li>
    </ul>
</nav>

<header>
    <h1>Historic</h1>
</header>

<nav>
    <ul>
        <li>
            <a onclick="hideSchedule()">
                About
            </a>
        </li>
        <li>
            <a onclick="hideAbout()">
                Schedule
            </a>
        </li>
        <li>
            <a href="home.php">
                Tickets
            </a>
        </li>
    </ul>
</nav>

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

<section id="about" class="historicContent">
    <article>
        <header>
            <h3>Guided Tour</h3>
        </header>

        <script>
            import {EditorState} from "/libs/Prosemirror/prosemirror-state-master"
            import {EditorView} from "/libs/Prosemirror/prosemirror-view-master"
            import {Schema, DOMParser} from "/libs/Prosemirror/prosemirror-model-master"
            import {schema} from "/libs/Prosemirror/prosemirror-schema-basic-master"
            import {addListNodes} from "/libs/Prosemirror/prosemirror-schema-list-master"
            import {exampleSetup} from "/libs/Prosemirror/prosemirror-example-setup-master"

            // Mix the nodes from prosemirror-schema-list into the basic schema to
            // create a schema with list support.
            const mySchema = new Schema({
                nodes: addListNodes(schema.spec.nodes, "paragraph block*", "block"),
                marks: schema.spec.marks
            })

            window.view = new EditorView(document.querySelector("#editor"), {
                state: EditorState.create({
                    doc: DOMParser.fromSchema(mySchema).parse(document.querySelector("#content")),
                    plugins: exampleSetup({schema: mySchema})
                })
            })
        </script>
    </article>
</section>
<section id="schedule" class="historicContent">
    <article>
        <header>
            <h3>
                Schedule
            </h3>
        </header>

        <table border="1">
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>No of English tours</th>
                <th>No of Dutch tours</th>
                <th>No of Chinese tours</th>
                <th>Start Location</th>
            </tr>
            <tr>
                <td>Thursday July 26</td>
                <td>10.00</td>
                <td>1</td>
                <td>1</td>
                <td>&dash;</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td></td>
                <td>13.00</td>
                <td>1</td>
                <td>1</td>
                <td>&dash;</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td></td>
                <td>16.00</td>
                <td>1</td>
                <td>1</td>
                <td>&dash;</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td>Friday July 27</td>
                <td>10.00</td>
                <td>1</td>
                <td>1</td>
                <td>&dash;</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td></td>
                <td>13.00</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td></td>
                <td>16.00</td>
                <td>1</td>
                <td>1</td>
                <td>&dash;</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td>Saturday July 28</td>
                <td>10.00</td>
                <td>2</td>
                <td>2</td>
                <td>&dash;</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td></td>
                <td>13.00</td>
                <td>2</td>
                <td>2</td>
                <td>1</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td></td>
                <td>16.00</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td>Sunday July 29</td>
                <td>10.00</td>
                <td>2</td>
                <td>2</td>
                <td>1</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td></td>
                <td>13.00</td>
                <td>3</td>
                <td>3</td>
                <td>2</td>
                <td>Bavo Church</td>
            </tr>
            <tr>
                <td></td>
                <td>16.00</td>
                <td>1</td>
                <td>1</td>
                <td>&dash;</td>
                <td>Bavo Church</td>
            </tr>
        </table>

        <header>
            <h3>Prices</h3>
        </header>

        <table border="1">
            <tr>
                <td>Single ticket</td>
                <td>17,50</td>
            </tr>
            <tr>
                <td>Family ticket (4 persons max.)</td>
                <td>60,&dash;</td>
            </tr>
        </table>

    </article>
</section>
</body>

</html>

