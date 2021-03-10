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

                <p>
                    Haarlem is very old city with a turbulent history and great sites to see. The city is full of culture and art, there are many museums you could visit, such as the Frans Hals museum, Teylers museum, museum van de Geest and of course many more! Besides museums, Haarlem had amazing architecture. The St. Bavo church is a beautiful church from the late middle ages, it is build in a gothic style which originated from Brabant. And to get a real feel of the Dutch culture, Molen de Adriaan is located in Haarlem.
                    So if you are interested in (Dutch) culture, the Historic tour is definitely something for you!
                </p>

                <p>
                    Haarlem Historic will be offered as a guided tour through the beautiful city of Haarlem, visiting historic and important places in its history. Duration of this walking tour will be approx. 2,5 hours (with a 15-minute break with refreshments). Tours will be held on Thursday, Friday, Saturday and Sunday
                    There will be several departures a day. The tour starts near of the ‘Church of St. Bavo’,
                    ‘Grote Markt’ in the centre of Haarlem. A giant flag marked ‘Haarlem Historic’ will mark the
                    exact starting location.
                </p>

                <p>
                    It is advised to wear good walking shoes for the tour.
                </p>

                <p>
                    <ul>
                        <li>
                            Prices (tour including one (1) drink p.p.):
                        </li>
                        <li>
                            Regular Participant: € 17,50
                        </li>
                        <li>
                            Family ticket (max. 4 participants): € 60, -
                        </li>
                    </ul>
                </p>

                <p>
                    Venues to visit are:
                    <ol>
                        <li>
                            Church of St. Bavo
                        </li>
                        <li>
                            Grote Markt
                        </li>
                        <li>
                            De Hallen
                        </li>
                        <li>
                            Proveniershof
                        </li>
                        <li>
                            Jopenkerk (Break location)
                        </li>
                        <li>
                            Waalse Kerk Haarlem
                        </li>
                        <li>
                            Molen de Adriaan
                        </li>
                        <li>
                            Amsterdamse Poort
                        </li>
                        <li>
                            Hof van Bakenes
                        </li>
                    </ol>
                    (Venues to visit are without prejudice)
                </p>
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
