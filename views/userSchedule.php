<?php

if (!isset($_SESSION)) session_start();

if (!isset($_SESSION["userType"])) {
    header("Location: login.php");
    exit;
} else {
?>

<html lang="en">
<head>
    <title>Schedule</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/schedule.css" />
</head>
<body>
    <?php require_once("menubar.php"); ?>

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
                require_once("services/TicketService.php");
                require_once("models/EventType.php");

                $ticketService = new TicketService();
                $tickets = $ticketService->getAllForUser((int)$_SESSION["userId"]);

                $tickets_thursday = [];
                $tickets_friday = [];
                $tickets_saturday = [];
                $tickets_sunday = [];

                if ($tickets == null) {
                    $tickets = [];
                }

                $tickets = array_map(function($twc) use ($ticketService) {
                    $ticket = $twc->ticket;
                    $start = $ticketService->getStartDate($ticket);
                    $end = $ticketService->getEndDate($ticket);

                    return ['ticket' => $ticket, 'start' => $start, 'end' => $end];
                }, $tickets);

                function timeDiff(DateTime $start, DateTime $end): int {
                    $start2 = new DateTime($start->format('H:i:s'));
                    $end2 = new DateTime($end->format('H:i:s'));

                    if ($start2 > $end2) {
                        $end2->add(new DateInterval('P1D'));
                    }

                    $diff = $start2->diff($end2);
                    $res = $diff->i / 30;
                    $res += $diff->h * 2;

                    return $res;
                }

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
</body>
</html>
<?php } ?>
