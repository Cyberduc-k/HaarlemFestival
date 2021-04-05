<?php

require_once("libs/TCPDF/config/tcpdf_config.php");
require_once("libs/TCPDF/tcpdf.php");
require_once("models/Invoice.php");
require_once("services/TicketService.php");

function generateInvoice(Invoice $invoice): TCPDF {
    $service = new TicketService();
    $tickets = $service->getAllForInvoice($invoice->getId());
    $pdf = new TCPDF();

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetFont('helvetica');
    $pdf->AddPage();

    $table = <<<END
        <table cellspacing="0" cellpadding="1" border="1">
            <thead>
                <th>Ticket</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </thead>
            <tbody>
    END;

    foreach ($tickets as $ticket) {
        $count = $ticket->count;
        $ticket = $ticket->ticket;

        $table .= "<tr><td>"
               . "</td><td>" . $count
               . "</td><td>" . $ticket->price
               . "</td><td>" . $ticket->price * $count
               . "</td></tr>";
    }

    $table .= '</tbody></table>';

    $pdf->writeHTML($table);

    return $pdf;
}

?>
