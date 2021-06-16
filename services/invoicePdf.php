<?php

require_once __DIR__.'/../libs/TCPDF/config/tcpdf_config.php';
require_once __DIR__.'/../libs/TCPDF/tcpdf.php';
require_once __DIR__.'/../models/Invoice.php';
require_once __DIR__.'/InvoiceService.php';
require_once __DIR__.'/TicketService.php';
require_once __DIR__.'/UserService.php';

function generateInvoice(Invoice $invoice): TCPDF {
    $tService = new TicketService();
    $uService = new UserService();
    $tickets = $tService->getAllForInvoice($invoice->getId());
    $user = $uService->getById($invoice->getUserId());
    $pdf = new TCPDF();

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetFont('helvetica');
    $pdf->SetTitle("Invoice");
    $pdf->AddPage();

    $pdf->setEqualColumns(2);
    $pdf->selectColumn(0);

    $pdf->Cell(25, 0, "NAME:", 0, false, 'R');
    $pdf->Cell(0, 0, $user->getFullName(), 0, true);

    $pdf->Cell(25, 0, "ADDRESS:", 0, false, 'R');
    $pdf->Cell(0, 0, $invoice->getUserAddress(), 0, true);

    $pdf->Cell(25, 0, "PHONE:", 0, false, 'R');
    $pdf->Cell(0, 0, $invoice->getUserPhone(), 0, true);

    $pdf->selectColumn(1);

    $pdf->Cell(25, 0, "DATE:", 0, false, 'R');
    $pdf->Cell(0, 0, $invoice->getDate()->format("d-m-Y"), 0, true);

    $pdf->Cell(25, 0, "DUE:", 0, false, 'R');
    $pdf->Cell(0, 0, $invoice->getDueDate()->format("d-m-Y"), 0, true);

    $pdf->Cell(25, 0, "INVOICE #:", 0, false, 'R');
    $pdf->Cell(0, 0, $invoice->getId(), 0, true);

    $pdf->resetColumns();

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
        $description = $tService->getDescription($ticket);
        $price = $ticket->getPrice();
        $subtotal = $price * $count;

        $table .= "<tr><td>" . $description
               . "</td><td>" . $count
               . "</td><td>€ " . $price
               . "</td><td>€ " . $subtotal
               . "</td></tr>";

        $total += $subtotal;
    }

    $subtotal = $total / (1.0 + $invoice->getTax());
    $table .= '<tr><td colspan="3" style="text-align: right">Subtotal</td>';
    $table .= '<td>€ '.round(($subtotal * 100.0)) / 100.0.'</td></tr>';
    $table .= '<tr><td colspan="3" style="text-align: right">Tax</td>';
    $table .= '<td> '.$invoice->getTax() * 100.0.'%</td></tr>';
    $table .= '<tr><td colspan="3" style="text-align: right">Total</td>';
    $table .= '<td>€ '.$total.'</td></tr>';
    $table .= '</tbody></table>';

    $pdf->writeHTML($table);

    return $pdf;
}

?>
