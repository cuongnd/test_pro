<?php 
require_once ('tcpdf.php');
$pdf = new TCPDF();
$pdf->addPage('', 'USLETTER');
$pdf->setFont('helvetica', '', 12);
$pdf->cell(30, 0, 'Hello World');
$pdf->Output('example_001.pdf', 'D');

?>