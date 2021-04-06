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

                    for ($i = 0; $i < 9 * 4; $i++) {
                        $thursday .= '<td></td>';
                        $friday .= '<td></td>';
                        $saturday .= '<td></td>';
                        $sunday .= '<td></td>';
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
