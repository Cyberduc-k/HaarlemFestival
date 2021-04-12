<html lang="en">
<head>
    <title>Programme</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/schedule.css" />
</head>
<body>
    <?php require_once __DIR__.'/menubar.php'; ?>

    <main class="content">
        <h1>Programme</h1>
        <table id="schedule" cellspacing="0">
            <thead>
                <tr>
                    <th></th>
                    <th>09:00</th>
                    <th>11:00</th>
                    <th>13:00</th>
                    <th>15:00</th>
                    <th>17:00</th>
                    <th>19:00</th>
                    <th>21:00</th>
                    <th>23:00</th>
                    <th>01:00</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Thursday</td>
                    <td colspan="9"></td>
                </tr>
                <tr>
                    <td>Friday</td>
                    <td colspan="9"></td>
                </tr>
                <tr>
                    <td>Saturday</td>
                    <td colspan="9"></td>
                </tr>
                <tr>
                    <td>Sunday</td>
                    <td colspan="9"></td>
                </tr>
            </tbody>
            <?php
                foreach ($tickets as $tkt) {
                    $diff = timeDiff($tkt['start'], $tkt['end']);

                    if ($diff > 0) {
                        $date = (int)$tkt['start']->format("d") - 26;
                        $end = new DateTime('02:00:00');
                        $end = timeDiff($tkt['end'], $end);
                        $event = EventType::getType($tkt['ticket']->getEventType());
                        $name = $ticketService->getDescription($tkt['ticket']);

                        echo '<div class="event ' . $event . '"';
                        echo ' style="--end: ' . $end . '; --day: ' . $date . '; --width: ' . $diff . ';">';
                        echo $name . '</div>';
                    }
                }
            ?>
        </table>
    </main>

    <?php require_once __DIR__.'/footer.php'; ?>
</body>
</html>
