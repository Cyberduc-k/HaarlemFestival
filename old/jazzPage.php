<html lang="en">
<head>
    <title>Jazz</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
require_once ("menubar.php");
?>

    <header>
        <h1>Jazz</h1>
    </header>

    <nav>
        <ul>
            <li>
                <a onclick="showAbout()">
                    About
                </a>
            </li>
            <li>
                <a onclick="showSchedule()">
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

    <section id="about" class="jazzPage">
        <p>
            About
        </p>
    </section>

    <section id="schedule" class="jazzPage">
        <nav>
            <ul>
                <li>
                    <a onclick="showThursday()">
                        Thursday
                    </a>
                </li>
                <li>
                    <a onclick="showFriday()">
                        Friday
                    </a>
                </li>
                <li>
                    <a onclick="showSaturday()">
                        Saturday
                    </a>
                </li>
                <li>
                    <a onclick="showSunday()">
                        Sunday
                    </a>
                </li>
            </ul>
        </nav>

        <section id="thursday" class="jazzDay">
            <p>
                Thursday
            </p>
        </section>

        <section id="friday" class="jazzDay">
            <p>
                Friday
            </p>
        </section>

        <section id="saturday" class="jazzDay">
            <p>
                Saturday
            </p>
        </section>

        <section id="sunday" class="jazzDay">
            <p>
                Sunday
            </p>
        </section>
    </section>

    <script>
        function hideAllPages() {
            document.querySelectorAll(".jazzPage").forEach(el => {
                el.style.display = "none";
            });
        }

        function showAbout() {
            hideAllPages();
            document.getElementById("about").style.display = "block";
            location.hash = "page=about";
        }

        function showSchedule() {
            hideAllPages();
            document.getElementById("schedule").style.display = "block";
            location.hash = "page=schedule";
        }

        function hideAllDays() {
            document.querySelectorAll(".jazzDay").forEach(el => {
                el.style.display = "none";
            });
        }

        function showThursday() {
            hideAllDays();
            document.getElementById("thursday").style.display = "block";
            location.hash = "page=schedule&day=thursday";
        }

        function showFriday() {
            hideAllDays();
            document.getElementById("friday").style.display = "block";
            location.hash = "page=schedule&day=friday";
        }

        function showSaturday() {
            hideAllDays();
            document.getElementById("saturday").style.display = "block";
            location.hash = "page=schedule&day=saturday";
        }

        function showSunday() {
            hideAllDays();
            document.getElementById("sunday").style.display = "block";
            location.hash = "page=schedule&day=sunday";
        }

        const hash = location.hash.substring(1);
        const pairs = hash.split('&');
        const vars = {};

        for (pair of pairs) {
            if (pair.length > 0) {
                const [k, v] = pair.split('=');

                vars[k] = v;
            }
        }

        let page;

        if (page = vars["page"]) {
            switch (page) {
                case "schedule":
                    showSchedule();

                    let day;
        
                    if (day = vars["day"]) {
                        switch (day) {
                            case "friday":
                                showFriday();
                                break;
                            case "saturday":
                                showSaturday();
                                break;
                            case "sunday":
                                showSunday();
                                break;
                            default:
                                showThursday();
                                break;
                        }
                    } else {
                        showThursday();
                    }

                    break;
                default:
                    showAbout();
                    break;
            }
        } else {
            showAbout();
        }
    </script>
</body>
</html>
