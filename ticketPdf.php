<?php

// @TODO: load ticket information from database
include_once("libs/TCPDF/config/tcpdf_config.php");
include_once("libs/TCPDF/tcpdf.php");

$pdf = new TCPDF();

$html = "Ticket";

$pdf->AddPage();
$pdf->writeHTMLCell(0, 0, "", "", $html, 0, 1, false, true, "", true);

$style = [
    'border' => 2,
    'padding' => "auto",
    'fgcolor' => [0, 0, 0],
    'bgcolor' => [255, 255, 255],
];

$pdf->write2DBarcode("url", "QRCODE,H", 160, 15, 50, 50, $style, "N");

ob_end_clean();

$pdf->Output("ticket.pdf");

?>
