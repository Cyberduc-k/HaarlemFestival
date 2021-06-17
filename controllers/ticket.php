<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/TicketService.php';

function run(int $id) {
    $ts = new TicketService();
    $ticket = $ts->getById($id);
    $userId = (int)$_SESSION['userId'];

    if (!isset($ticket) || !$ts->belongsToUser($id, $userId)) {
        require __DIR__.'/../views/404.php';
        return;
    }

    require __DIR__.'/../views/ticket.php';
}
