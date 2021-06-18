<?php

require_once("libs/TCPDF/config/tcpdf_config.php");
require_once("libs/TCPDF/tcpdf.php");
require_once("models/User.php");
require_once("models/UserTypes.php");
require_once("models/Ticket.php");
require_once("models/TicketType.php");
require_once("models/EventType.php");
require_once("services/TicketService.php");

function generateTickets(User $user): TCPDF {
    $service = new TicketService();
    $tickets = $service->getAllForCart($user->getId());
    $pdf = new TCPDF();
    $pdf->AddPage();

    $white = [255, 255, 255];
    $gray = [100, 100, 100];
    $black = [0, 0, 0];

    $width = $pdf->getPageWidth();
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $i = 0;

    foreach ($tickets as $twc) {
        $ticket = $twc->ticket;
        $count = $twc->count;

        for ($x = 1; $x <= $count; $x++) {
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetFont('helvetica', 'B');


            // draw shapes
            $pdf->SetLineStyle(['width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $black]);
            $pdf->RoundedRect($x, $y, $width - $x * 2, 60, 3.50, '1111', '');
            $pdf->RoundedRect($width - $x - 50, $y, 50, 6, 3.5, '1000', 'DF', [], $black);
            $pdf->RoundedRect($x, $y, 10, 60, 3.5, '0011', 'DF', [], [255, 100, 0]);
            $pdf->RoundedRect($width - $x - 50, $y + 40, 50, 20, 3.5, '0100', 'DF', [], [255, 100, 0]);
            $pdf->Line($width - $x - 50, $y, $width - $x - 50, $y + 60, []);
            $pdf->Circle($width - $x - 50, $y - 0.272, 1.5, 180, 360, 'DF', [], $white);
            $pdf->Circle($width - $x - 50, $y + 60 + 0.272, 1.5, 0, 180, 'DF', [], $white);

            $pdf->SetFontSize(10);
            $pdf->SetTextColorArray($gray);
            $pdf->Text($x + 12, $y + 3, "EVENT");
            $pdf->Text($x + 12, $y + 18, "LOCATION");
            $pdf->Text($x + 12, $y + 33, "FROM");
            $pdf->Text($x + 12, $y + 45, "TILL");

            $pdf->SetFontSize(12);
            $pdf->SetTextColorArray($black);
            $pdf->Text($x + 12, $y + 8, strtoupper(EventType::getType($ticket->getEventType())));
            $pdf->Text($x + 12, $y + 23, strtoupper($service->getLocation($ticket)));
            $pdf->Text($x + 12, $y + 38, strtoupper($service->getStartDate($ticket)->format("M j, Y \A\T h:i A")));
            $pdf->Text($x + 12, $y + 50, strtoupper($service->getEndDate($ticket)->format("M j, Y \A\T h:i A")));

            $pdf->SetFontSize(8);
            $pdf->SetTextColorArray($white);
            $pdf->Text($width - $x - 47, $y + 43, strtoupper(TicketType::getType($ticket->getType())));

            $pdf->SetTextColorArray($black);
            $pdf->Text($width - $x - 47, $y + 48, strtoupper(UserTypes::getType($user->getUsertype())));
            $pdf->Text($width - $x - 47, $y + 53, strtoupper($user->getEmail()));

            $style = [
                'border' => 0,
                'padding' => "0000",
                'fgcolor' => [0, 0, 0],
                'bgcolor' => [255, 255, 255],
            ];

            $pdf->write2DBarcode("http://www.HaarlemFestival2021.nl/ticket/" . $ticket->getId(), "QRCODE,H", 165, $y + 13, 20, 20, $style, "N");

            $i++;
            $y += 70;

            if ($i % 4 == 0) {
                $pdf->AddPage();
                $y = $pdf->getY();
            }
        }
    }

    return $pdf;
}

?>
