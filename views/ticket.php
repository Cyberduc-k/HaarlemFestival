<html lang="en">
<head>
    <title>Ticket</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/ticket.css" />
    <script src="/js/hash.js"></script>
</head>
<body>
<?php require __DIR__.'/menubar.php'; ?>
<main>

    <section id="ticket">
        <article>
            <header>
                <h2>Your ticket information</h2>
            </header>

            <table>
                <thead>
                <tr>
                    <th>Event</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Start time</th>
                    <th>End time</th>
                    <?php if ($ticket->getEventType() == EventType::Food) { ?>
                    <th>Comment</th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="td"><?php echo EventType::getType($ticket->getEventType()); ?></td>
                        <td id="td"><?php echo $ts->getDescription($ticket); ?></td>
                        <td id="td"><?php echo $ts->getLocation($ticket); ?></td>
                        <td id="td"><?php echo $ticket->getPrice(); ?></td>
                        <td id="td"><?php echo $ts->getStartDate($ticket)->format("H:i:s"); ?></td>
                        <td id="td"><?php echo $ts->getEndDate($ticket)->format("H:i:s") ?></td>
                        <td id="td">
                            <?php if ($ticket->getEventType() == EventType::Food) {

                                require_once __DIR__.'/../services/ReservationService.php';

                                $rs = new ReservationService();

                                $reservation = $rs->getById($ticket->getEventId());

                                echo $reservation->getComment();

                            } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </article>
    </section>

</main>
<?php require __DIR__.'/footer.php'; ?>
</body>
</html>