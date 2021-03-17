<?php

require_once("libs/TCPDF/config/tcpdf_config.php");
require_once("libs/TCPDF/tcpdf.php");
require_once("models/User.php");
require_once("models/UserTypes.php");
require_once("models/Ticket.php");
require_once("models/TicketType.php");
require_once("models/EventType.php");
require_once("services/TicketService.php");

function generateTickets(User $user): void {
    $service = new TicketService();
    $tickets = $service->getAllForUser($user->getId());
    $pdf = new TCPDF();

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();

    $white = [255, 255, 255];
    $gray = [100, 100, 100];
    $black = [0, 0, 0];
    $i = 0;

    foreach ($tickets as $ticket) {
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $width = $pdf->getPageWidth();

        // draw shapes
        $pdf->SetLineStyle(['width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $black]);
        $pdf->RoundedRect($x, $y, $width - $x * 2, 60, 3.50, '1111', '');
        $pdf->RoundedRect($width - $x - 50, $y, 50, 6, 3.5, '1000', 'DF', [], $black);
        $pdf->RoundedRect($x, $y, 10, 60, 3.5, '0011', 'DF', [], [255, 100, 0]);
        $pdf->RoundedRect($width - $x - 50, $y + 40, 50, 20, 3.5, '0100', 'DF', [], [255, 100, 0]);
        $pdf->Line($width - $x - 50, $y, $width - $x - 50, $y + 60, []);
        $pdf->Circle($width - $x - 50, $y - 0.272, 1.5, 180, 360, 'DF', [], $white);
        $pdf->Circle($width - $x - 50, $y + 60 + 0.272, 1.5, 0, 180, 'DF', [], $white);

        $pdf->SetFont('helvetica', 'B');

        $pdf->SetFontSize(10);
        $pdf->SetTextColorArray($gray);
        $pdf->Text($x + 12, $y + 3, "EVENT");
        $pdf->Text($x + 12, $y + 18, "LOCATION");
        $pdf->Text($x + 12, $y + 33, "DATE AND TIME");
        $pdf->Text($x + 12, $y + 45, "TO");

        $pdf->SetFontSize(12);
        $pdf->SetTextColorArray($black);
        $pdf->Text($x + 12, $y + 8, strtoupper(EventType::getType($ticket->getEventType())));
        $pdf->Text($x + 12, $y + 23, strtoupper($service->getLocation($ticket))); // TODO: $service->getLocation($ticket)
        $pdf->Text($x + 12, $y + 38, "AUG 9, 2021 AT 08:00 PM"); // TODO: $service->getStartDate($ticket)
        $pdf->Text($x + 12, $y + 50, "AUG 9, 2021 AT 09:00 PM"); // TODO: $service->getEndDate($ticket)

        $pdf->SetFontSize(8);
        $pdf->SetTextColorArray($white);
        $pdf->Text($width - $x - 47, $y + 43, strtoupper(TicketType::getType($ticket->getType()))); // TODO: add ticketType

        $pdf->SetTextColorArray($black);
        $pdf->Text($width - $x - 47, $y + 48, strtoupper(UserTypes::getType($user->getUsertype())));
        $pdf->Text($width - $x - 47, $y + 53, strtoupper($user->getEmail()));

        $style = [
            'border' => 0,
            'padding' => "0000",
            'fgcolor' => [0, 0, 0],
            'bgcolor' => [255, 255, 255],
        ];

        $pdf->write2DBarcode("url", "QRCODE,H", 165, 22, 20, 20, $style, "N");

        $i++;

        if ($i % 4 == 0) {
            $pdf->AddPage();
        }
    }

    $pdf->Output("ticket.pdf", 'F');
}

?>
