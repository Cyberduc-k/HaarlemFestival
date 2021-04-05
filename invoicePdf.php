<?php

require_once("libs/TCPDF/config/tcpdf_config.php");
require_once("libs/TCPDF/tcpdf.php");
require_once("models/Invoice.php");
require_once("services/InvoiceService.php");
require_once("services/TicketService.php");

function generateInvoice(Invoice $invoice): TCPDF {
    $service = new TicketService();
    $tickets = $service->getAllForInvoice($invoice->getId());
    $pdf = new TCPDF();

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetFont('helvetica');
    $pdf->AddPage();

    $pdf->Cell(25, 0, "DATE:", 0, false, 'R');
    $pdf->Cell(0, 0, $invoice->getDate()->format("d-m-Y"), 0, true);

    $pdf->Cell(25, 0, "DUE:", 0, false, 'R');
    $pdf->Cell(0, 0, $invoice->getDueDate()->format("d-m-Y"), 0, true);

    $pdf->Cell(25, 0, "INVOICE #:", 0, false, 'R');
    $pdf->Cell(0, 0, $invoice->getId(), 0, true);

    $pdf->Ln();
    $pdf->Ln();

    $table = <<<END
        <table cellspacing="0" cellpadding="2" border="1">
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
    END;

    $total = 0;

    foreach ($tickets as $ticket) {
        $count = $ticket->count;
        $ticket = $ticket->ticket;
        $description = $service->getDescription($ticket);
        $price = $ticket->getPrice();
        $subtotal = $price * $count;

        $table .= "<tr><td>" . $description
               . "</td><td>" . $count
               . "</td><td>€ " . $price
               . "</td><td>€ " . $subtotal
               . "</td></tr>";

        $total += $subtotal;
    }

    $table .= '</tbody></table>';

    $pdf->writeHTML($table);

    $border = ['all' => ['width' => 0.4]];

    $pdf->Rect(152.5, 56.55, 47.47, 6.7, '', $border);
    $pdf->Rect(152.5, 63.25, 47.47, 6.7, '', $border);
    $pdf->Rect(152.5, 69.95, 47.47, 6.7, '', $border);

    $pdf->Text(140, 57.35, "Total");
    $pdf->Text(152, 57.35, "€ $total");

    $pdf->Text(142, 64.05, "Tax");
    $pdf->Text(152, 64.05, $invoice->getTax() * 100.0 . "%");

    $pdf->Text(131.5, 70.75, "Total Due");
    $pdf->Text(152, 70.75, "€ " . $total * (1.0 + $invoice->getTax()));

    return $pdf;
}

?>
