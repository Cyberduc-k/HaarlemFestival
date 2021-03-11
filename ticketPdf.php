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

// draw shapes
$pdf->SetLineStyle(['width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => [0, 0, 0]]);
$pdf->RoundedRect($x, $y, $width - $x * 2, 60, 3.50, '1111', '');
$pdf->RoundedRect($width - $x - 50, $y, 50, 6, 3.5, '1000', 'DF', [], [0, 0, 0]);
$pdf->RoundedRect($x, $y, 10, 60, 3.5, '0011', 'DF', [], [255, 100, 0]);
$pdf->RoundedRect($width - $x - 50, $y + 40, 50, 20, 3.5, '0110', 'DF', [], [255, 100, 0]);
$pdf->Line($width - $x - 50, $y, $width - $x - 50, $y + 60, []);
$pdf->Circle($width - $x - 50, $y - 0.27, 1.5, 180, 360, 'DF', [], [255, 255, 255]);
$pdf->Circle($width - $x - 50, $y + 60 + 0.27, 1.5, 0, 180, 'DF', [], [255, 255, 255]);

$gray = [100, 100, 100];
$black = [0, 0, 0];

$pdf->SetFont('helvetica', 'B');
$pdf->SetFontSize(10);
$pdf->SetTextColorArray($gray);
$pdf->Text($x + 12, $y + 3, "EVENT");

$pdf->SetFontSize(12);
$pdf->SetTextColorArray($black);
$pdf->Text($x + 12, $y + 8, "JAZZ");

$pdf->SetFontSize(10);
$pdf->SetTextColorArray($gray);
$pdf->Text($x + 12, $y + 18, "LOCATION");

$pdf->SetFontSize(12);
$pdf->SetTextColorArray($black);
$pdf->Text($x + 12, $y + 23, "PATRONAAT (MAIN HALL)");

$pdf->SetFontSize(10);
$pdf->SetTextColorArray($gray);
$pdf->Text($x + 12, $y + 33, "DATE AND TIME");

$pdf->SetFontSize(12);
$pdf->SetTextColorArray($black);
$pdf->Text($x + 12, $y + 38, "AUG 9, 2021 AT 08:00 PM");

$pdf->SetFontSize(10);
$pdf->SetTextColorArray($gray);
$pdf->Text($x + 12, $y + 45, "TO");

$pdf->SetFontSize(12);
$pdf->SetTextColorArray($black);
$pdf->Text($x + 12, $y + 50, "AUG 9, 2021 AT 09:00 PM");

$pdf->SetFontSize(8);
$pdf->SetTextColorArray([255, 255, 255]);
$pdf->Text($width - $x - 47, $y + 43, "1 ACCESS PASS");

$pdf->SetTextColorArray($black);
$pdf->Text($width - $x - 47, $y + 48, "ADMIN");
$pdf->Text($width - $x - 47, $y + 53, "TTTYMO@GMAIL.COM");

// $pdf->writeHTMLCell(
//     0, 0,
//     '', '',
//     $html, 1, 1,
//     false, true, '', true
// );

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
