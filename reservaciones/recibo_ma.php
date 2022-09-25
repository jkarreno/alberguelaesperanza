<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

require('../libs/fpdf/fpdf.php');

$ResRec=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM material_apoyo_recibo WHERE Id='".$_GET["recibo"]."' LIMIT 1"));

$ResSMA=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM material_apoyo_inventario WHERE IdReservacion='".$ResRec["IdReservacion"]."' AND ES='S' AND IdReservacion!='0' LIMIT 1"));
$ResEMA=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM material_apoyo_inventario WHERE IdReservacion='".$ResRec["IdReservacion"]."' AND ES='E' AND IdReservacion!='0' LIMIT 1"));

$ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservacion WHERE Id='".$ResRec["IdReservacion"]."' LIMIT 1"));

$ResMat=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM material_apoyo WHERE Id='".$ResSMA["IdMaterial"]."' LIMIT 1"));

if($ResSMA["PA"]=='P')
{
    $ResRecibe=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos FROM pacientes WHERE Id='".$ResSMA["IdPA"]."' LIMIT 1"));
}
elseif($ResSMA["PA"]=='A')
{
    $ResRecibe=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos FROM acompannantes WHERE Id='".$ResSMA["IdPA"]."' LIMIT 1"));
}

if($ResEMA["PA"]=='P')
{
    $ResDevuelve=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos FROM pacientes WHERE Id='".$ResEMA["IdPA"]."' LIMIT 1"));
}
elseif($ResEMA["PA"]=='A')
{
    $ResDevuelve=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos FROM acompannantes WHERE Id='".$ResEMA["IdPA"]."' LIMIT 1"));
}

//crear el nuevo archivo pdf
$pdf=new FPDF('P', 'mm', 'Letter');

//desabilitar el corte automatico de pagina
$pdf->SetAutoPageBreak(false);

//Agregamos la primer pagina
$pdf->AddPage();

//marco del recibo
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(4);
$pdf->SetX(4);
$pdf->Cell(208,90,'',1,0,'L', FALSE, 1);
//logo 
$pdf->Image('../images/logo.png',140,8,70);
//Titulo
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(8);
$pdf->SetX(8);
$pdf->Cell(60,4,utf8_decode('RECIBO DE MATERIAL DE APOYO'),0,0,'L',0);
//
$pdf->SetY(16);
$pdf->SetX(8);
$pdf->Cell(60,4,'Folio: ',0,0,'L',0);
//
$pdf->SetTextColor(255,000,000);
$pdf->SetX(28);
$pdf->Cell(20,4,$ResRec["Id"],'B',0,'L',0);
//
$pdf->SetTextColor(000,000,000);
$pdf->Cell(20,4,'Fecha: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(60,4,fecha($ResRec["Fecha"]),'B',0,'L',0);
//reservación
$pdf->SetY(24);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(32,4,utf8_decode('Reservación: '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(20,4,$ResRec["IdReservacion"],'B',0,'L',0);
//
$pdf->SetFont('Arial','B',14);
$pdf->Cell(25,4,utf8_decode('Registro: '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(43,4,$ResRes["IdPaciente"],'B',0,'L',0);
//material
$pdf->SetY(32);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(25,4,utf8_decode('Material: '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(95,4,$ResMat["Nombre"],'B',0,'L',0);
//recibe
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(40);
$pdf->SetX(8);
$pdf->Cell(60,40,'',1,0,'L', FALSE, 1);
//
$pdf->SetY(42);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,4,utf8_decode('Recibe'),0,0,'C',0);
//
$pdf->SetY(70);
$pdf->SetX(8);
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,4,utf8_decode($ResRecibe["Nombre"].' '.$ResRecibe["Apellidos"]),'T',0,'C',0);
//
$pdf->SetY(74);
$pdf->Cell(60,4,fecha($ResSMA["Fecha"]),0,0,'C',0);
//devuelve
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(40);
$pdf->SetX(72);
$pdf->Cell(60,40,'',1,0,'L', FALSE, 1);
//
$pdf->SetY(42);
$pdf->SetX(72);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,4,utf8_decode('Devuelve'),0,0,'C',0);
//
$pdf->SetY(70);
$pdf->SetX(72);
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,4,utf8_decode($ResDevuelve["Nombre"].' '.$ResDevuelve["Apellidos"]),'T',0,'C',0);
//
$pdf->SetY(74);
$pdf->SetX(72);
$pdf->Cell(60,4,fecha($ResEMA["Fecha"]),0,0,'C',0);
//observaciones
//
$pdf->SetY(42);
$pdf->SetX(134);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,4,utf8_decode('Observaciones: '),0,0,'L',0);
//
$pdf->SetY(48);
$pdf->SetX(134);
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(75,4,utf8_decode($ResSMA["Observaciones"]),0,'L',0);
//
$pdf->SetY(82);
$pdf->SetX(8);
$pdf->SetFont('Arial','',10);
$pdf->Cell(200,4,utf8_decode('Xontepec No. 105 Col. Toriello Guerra, Tlalpan, C.P. 14050 CDMX'),0,0,'C',0);
//
$pdf->SetY(88);
$pdf->SetX(8);
$pdf->Cell(200,4,utf8_decode('Tels.: 55-5606-8916 55-5666-9428 R.F.C. VVA920903N10'),0,0,'C',0);


//Envio Archivo
$pdf->Output();