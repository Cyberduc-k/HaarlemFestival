<?php

require_once __DIR__.'/../services/TicketService.php';

function run(int $id) {

    $ts = new TicketService();
    $ticket = $ts->getById($id);

    if (!isset($ticket)) {
        require __DIR__.'/../views/404.php';
        return;
    }

    require __DIR__.'/../views/ticket.php';
}





