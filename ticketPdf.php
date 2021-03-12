<?php

// @TODO: load ticket information from database
include_once("libs/TCPDF/config/tcpdf_config.php");
include_once("libs/TCPDF/tcpdf.php");

$pdf = new TCPDF();

$html = "Ticket";

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

$x = $pdf->GetX();
$y = $pdf->GetY();
$width = $pdf->getPageWidth();
$white = [255, 255, 255];
$gray = [100, 100, 100];
$black = [0, 0, 0];

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
$pdf->Text($x + 12, $y + 8, "JAZZ");
$pdf->Text($x + 12, $y + 23, "PATRONAAT (MAIN HALL)");
$pdf->Text($x + 12, $y + 38, "AUG 9, 2021 AT 08:00 PM");
$pdf->Text($x + 12, $y + 50, "AUG 9, 2021 AT 09:00 PM");

$pdf->SetFontSize(8);
$pdf->SetTextColorArray($white);
$pdf->Text($width - $x - 47, $y + 43, "SINGLE ACCESS PASS");

$pdf->SetTextColorArray($black);
$pdf->Text($width - $x - 47, $y + 48, "ADMIN");
$pdf->Text($width - $x - 47, $y + 53, "TTTYMO@GMAIL.COM");

$style = [
    'border' => 0,
    'padding' => "0000",
    'fgcolor' => [0, 0, 0],
    'bgcolor' => [255, 255, 255],
];

$pdf->write2DBarcode("url", "QRCODE,H", 165, 22, 20, 20, $style, "N");

ob_end_clean();

$pdf->Output("ticket.pdf");

?>
