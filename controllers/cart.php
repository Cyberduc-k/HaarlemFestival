<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__ . '/../services/TicketService.php';
require_once __DIR__ . '/../services/PaymentService.php';

if (!isset($_SESSION['userId'])) {
    header("Location: /login");
    exit;
}

$userId = $_SESSION['userId'];
$ticketService = new TicketService();
$tickets = $ticketService->getAllForCart($userId);

if ($_POST) {
    if (!empty($_POST['addTicketButton'])) {
        $ticketId = $_GET['ticketId'];
        foreach ($tickets as $twc) {
            $ticket = $twc->ticket;
            if ($ticket->getId() == $ticketId) {
                $amount = $twc->count;
                if ($amount <= $ticket->getInStock() - 1 && $ticket->getEventType() != 1
                    && $ticket->getEventType() != 2) {
                    $amount++;
                    $ticketService->updateCart($userId, $ticketId, $amount);
                } else if($ticket->getEventType() == 1 && $amount <= 14){
                    $amount ++;
                    $ticketService->updateCart($userId, $ticketId, $amount);
                } else if($ticket->getEventType() == 2 && amount <=14){
                    $amount ++;
                    $ticketService->updateCart($userId, $ticketId, $amount);
                }
                else {
                    $_SESSION['cartAmountError'] = "We're sorry, but you have currently selected the maximum amount of available tickets";
                    header("Location: /cart");
                    exit;
                }
            }
            header("Location: /cart");
        }
    }
    if (!empty($_POST['removeTicketButton'])) {
        $ticketId = $_GET['ticketId'];
        foreach ($tickets as $twc) {
            $ticket = $twc->ticket;
            if ($ticket->getId() == $ticketId) {
                $amount = $twc->count;
                if ($amount >= 2) {
                    $amount--;
//                    $ticketService->addBackToStock($ticket->getId());
                    $ticketService->updateCart($userId, $ticketId, $amount);
                }
            }
        }
        header("location: /cart");
    }

    if (!empty($_POST['deleteTicketsButton'])) {
        $ticketId = $_GET['ticketId'];
        foreach ($tickets as $twc) {
            $ticket = $twc->ticket;
            if ($ticket->getId() == $ticketId) {
                $amount = $twc->count;
//                $ticketService->cancelTicketOrder($ticketId, $amount);
                $ticketService->deleteFromCart($userId, $ticketId);
                $_SESSION['cartTicketsRemoved'] = "removed tickets";
            }
        }
        header("Location: /cart");
        exit;
    }

    if (!empty($_POST['agreeButton'])) {
        $ps = new PaymentService();
        $total = 0;

        foreach ($tickets as $twc) {
            $ticket = $twc->ticket;
            $amount = $twc->count;
            $price = $ticket->getPrice();
            $ticketPrice = $amount * $price;
            $total += $ticketPrice;

        }
        $total = number_format($total, 2, '.', '');
        $total = (string) $total;
        $ps->createPayment($userId, $total);
    } else {
        header("Location: /cart");
        exit;
    }
} else {
    require __DIR__ . '/../views/cart.php';
}
