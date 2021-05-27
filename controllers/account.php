<?php

if(!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/UserService.php';
require_once __DIR__ . '/../services/TicketService.php';
require_once __DIR__ . '/../services/PaymentService.php';

$userService = new UserService();
$ticketService = new TicketService();
$tickets = $ticketService->getAllForOverview($_SESSION['userId']);

$avatar = $userService->getAvatarByEmail($_SESSION['email']);
$firstName = $_SESSION['firstname'];


require __DIR__.'/../views/account.php';

?>
