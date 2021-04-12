<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/TicketService.php';
require_once __DIR__.'/../models/EventType.php';

if (!isset($_SESSION['userId'])) {
    header("Location: /login");
    exit;
} else {
    $ticketService = new TicketService();
    $tickets = $ticketService->getAllForUser((int)$_SESSION["userId"]);

    if ($tickets == null) {
        $tickets = [];
    }

    $tickets = array_map(function($twc) use ($ticketService) {
        $ticket = $twc->ticket;
        $start = $ticketService->getStartDate($ticket);
        $end = $ticketService->getEndDate($ticket);

        return ['ticket' => $ticket, 'start' => $start, 'end' => $end];
    }, $tickets);

    require __DIR__.'/../views/programme.php';
}

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
