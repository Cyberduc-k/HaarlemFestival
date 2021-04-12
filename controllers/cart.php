<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/TicketService.php';
require_once __DIR__.'/../services/PaymentService.php';

if (!isset($_SESSION['userId'])) {
    header("Location: /login");
    exit;
}

$userId = $_SESSION['userId'];
$ticketService = new TicketService();
$tickets = $ticketService->getAllForCart($userId);

if ($_POST) {
    if (!empty($_POST['agreeButton'])) {
        $ps = new PaymentService();
        $total = 0;

        foreach ($tickets as $twc) {
            $ticket = $twc->ticket;
            $price = $ticket->getPrice();
            $total += $price;
        }

        $ps->createPayment($userId, $total);
    } else {
        header("Location: /cart");
        exit;
    }
} else {
    require __DIR__.'/../views/cart.php';
}
