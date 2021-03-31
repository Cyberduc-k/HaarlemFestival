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
    $pdf->AddPage();

    return $pdf;
}

?>
