<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

require('../libs/fpdf/fpdf.php');

//crear el nuevo archivo pdf
$pdf=new FPDF();

//desabilitar el corte automatico de pagina
$pdf->SetAutoPageBreak(false);

//Agregamos la primer pagina
$pdf->AddPage('P', 'Letter');

//marco del titulo
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(4);
$pdf->SetX(4);
$pdf->Cell(208,20,'',1,0,'L', FALSE, 1);
$pdf->SetY(4);
$pdf->SetX(4);
$pdf->Cell(30,20,'',1,0,'L', FALSE, 1);
//logo 
$pdf->Image('../images/logo.png',8,10,20);
//titulo
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(6);
$pdf->SetX(34);
$pdf->Cell(178,4,'Voluntarias Vicentinas Albergue La Esperanza I.A.P.',0,0,'C',0);
$pdf->SetY(12);
$pdf->SetX(34);
$pdf->Cell(178,4,'Existencias en Almacen',0,0,'C',0);
$pdf->SetY(18);
$pdf->SetX(34);
$pdf->Cell(178,4,fecha(date("Y-m-d")),0,0,'C',0);

//Envio Archivo
$pdf->Output();

