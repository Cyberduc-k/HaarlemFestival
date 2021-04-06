<?php

if (!isset($_SESSION)) session_start();

// if (!isset($_SESSION["userType"])) {
    // header("Location: login.php");
    // exit
// } else {
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
                    <th colspan="4">09:00</th>
                    <th colspan="4">11:00</th>
                    <th colspan="4">13:00</th>
                    <th colspan="4">15:00</th>
                    <th colspan="4">17:00</th>
                    <th colspan="4">19:00</th>
                    <th colspan="4">21:00</th>
                    <th colspan="4">23:00</th>
                    <th colspan="4">01:00</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    require_once("services/TicketService.php");

                    $ticketService = new TicketService();
                    $tickets = $ticketService->getAllForUser(0);
                    $thursday = '<tr><td>Thursday</td>';
                    $friday = '<tr><td>Friday</td>';
                    $saturday = '<tr><td>Saturday</td>';
                    $sunday = '<tr><td>Sunday</td>';

                    $tickets_thursday = [];
                    $tickets_friday = [];
                    $tickets_saturday = [];
                    $tickets_sunday = [];

                    if ($tickets == null) {
                        $tickets = [];
                    }

                    foreach ($tickets as $ticket) {
                        $start = $ticketService->getStartDate($ticket);
                        $end = $ticketService->getEndDate($ticket);
                        $date = (int)$start->format('d');

                        switch ($date) {
                            case 26:
                                array_push($tickets_thursday, ['ticket' => $ticket, 'start' => $start, 'end' => $end]);
                                break;
                            case 27:
                                array_push($tickets_friday, ['ticket' => $ticket, 'start' => $start, 'end' => $end]);
                                break;
                            case 28:
                                array_push($tickets_saturday, ['ticket' => $ticket, 'start' => $start, 'end' => $end]);
                                break;
                            default:
                                array_push($tickets_sunday, ['ticket' => $ticket, 'start' => $start, 'end' => $end]);
                                break;
                        }
                    }

                    $sort_fn = function(array $a, array $b): int {
                        if ($a['start'] == $b['start']) {
                            return 0;
                        } else if ($a['start'] < $b['start']) {
                            return -1;
                        } else {
                            return 1;
                        }
                    };

                    usort($tickets_thursday, $sort_fn);
                    usort($tickets_friday, $sort_fn);
                    usort($tickets_saturday, $sort_fn);
                    usort($tickets_sunday, $sort_fn);

                    processDay($thursday, $tickets_thursday);
                    processDay($friday, $tickets_friday);
                    processDay($saturday, $tickets_saturday);
                    processDay($sunday, $tickets_sunday);

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

                    function processDay(string &$text, array $tickets) {
                        $time = new DateTime('08:00:00');

                        foreach ($tickets as $tkt) {
                            for ($i = timeDiff($time, $tkt['start']); $i > 0; $i--) {
                                $text .= '<td></td>';
                            }

                            $text .= '<td colspan="' . timeDiff($tkt['start'], $tkt['end']) . '"></td>';
                            $time = $tkt['end'];
                        }

                        for ($i = timeDiff($time, new DateTime('02:00:00')); $i > 0; $i--) {
                            $text .= '<td></td>';
                        }
                    }

                    echo $thursday; echo '</td>';
                    echo $friday; echo '</td>';
                    echo $saturday; echo '</td>';
                    echo $sunday; echo '</td>';
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>
