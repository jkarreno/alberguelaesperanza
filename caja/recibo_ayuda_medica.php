<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

require('../libs/fpdf/fpdf.php');

$ResRe=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pagogastosmedicos WHERE Id='".$_GET["idrecibo"]."' LIMIT 1"));
$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos, Apellidos2 FROM pacientes WHERE Id='".$ResRe["IdPaciente"]."' LIMIT 1"));

if($ResRe["PA"]=='A')
{
    $ResAco=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos, Apellidos2 FROM acompannantes WHERE Id='".$ResRe["IdPA"]."' LIMIT 1"));
    $recibe=$ResAco["Nombre"].' '.$ResAco["Apellidos"].' '.$ResAco["Apellidos2"];
}
else
{
    $recibe=$ResPac["Nombre"].' '.$ResPac["Apellidos"].' '.$ResPac["Apellidos2"];
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
$pdf->Cell(208,112,'',1,0,'L', FALSE, 1);
//logo 
$pdf->Image('../images/logo.png',140,8,70);
//Titulo
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(8);
$pdf->SetX(8);
$pdf->Cell(60,4,utf8_decode('FORMATO DE GASTOS MÉDICOS Y REEMBOLSOS'),0,0,'L',0);
//
$pdf->SetY(16);
$pdf->SetX(8);
$pdf->Cell(60,4,'Folio: ',0,0,'L',0);
//
$pdf->SetTextColor(255,000,000);
$pdf->SetX(28);
$pdf->Cell(20,4,$ResRe["Id"],'B',0,'L',0);
//
$pdf->SetTextColor(000,000,000);
$pdf->Cell(20,4,'Fecha: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(60,4,fecha($ResRe["Fecha"]),'B',0,'L',0);
//
$pdf->SetY(24);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(23,4,'Registro: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(17,4,$ResRe["IdPaciente"],'B',0,'L',0);
//
$pdf->SetFont('Arial','B',14);
$pdf->Cell(20,4,'Ayuda: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(17,4,$ResRe["TipoAyuda"].' %','B',0,'L',0);
//
$pdf->SetY(32);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(23,4,'Paciente: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(105,4,utf8_decode($ResPac["Nombre"].' '.$ResPac["Apellidos"]),'B',0,'L',0);
//
$pdf->SetY(40);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(23,4,'Recibe: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(105,4,utf8_decode($recibe),'B',0,'L',0);
//
$pdf->SetY(48);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(40,4,utf8_decode('La cantidad de : '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$cantidad=explode('.', $ResRe["CantidadEntregada"]);
$pdf->Cell(160,4,'$ '.number_format($cantidad[0], 2).' ('.num2letras($cantidad[0]).' pesos '.$cantidad[1].'/100 M.N.)','B',0,'L',0);
//
$pdf->SetY(56);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(43,4,utf8_decode('Por concepto de: '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(157,4,utf8_decode($ResRe["Concepto"]),'B',0,'L',0);
//
$pdf->SetY(64);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(28,4,utf8_decode('Proveedor: '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(173,4,utf8_decode($ResRe["Provedor"]),'B',0,'L',0);
//
$pdf->SetY(72);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(35,4,utf8_decode('Num. Factura: '),0,0,'L',0);
$pdf->SetFont('Arial','',14);
$pdf->Cell(28,4,utf8_decode($ResRe["NumFactura"]),'B',0,'L',0);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(37,4,utf8_decode('Monto Factura: '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$cantidadf=explode('.', $ResRe["CantidadFactura"]);
$pdf->Cell(100,4,'$ '.number_format($cantidadf[0], 2).' ('.num2letras($cantidadf[0]).' pesos '.$cantidadf[1].'/100 M.N.)','B',0,'L',0);
//
//autoriza
$pdf->SetY(94);
$pdf->SetX(20);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(80,4,utf8_decode('Autoriza'),'T',0,'C',0);
//
$ResAutorizo=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM usuarios WHERE Id='".$ResRe["Autorizo"]."' LIMIT 1"));
$pdf->SetY(98);
$pdf->SetX(20);
$pdf->SetFont('Arial','',12);
$pdf->Cell(80,4,utf8_decode($ResAutorizo["Nombre"]),0,0,'C',0);
//recibio
$pdf->SetY(94);
$pdf->SetX(120);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(80,4,utf8_decode('Recibio'),'T',0,'C',0);
//
if ($ResRe["PA"]=='P')
{
    $nombrer='P. '.$recibe;
}
elseif($ResRe["PA"]=='A')
{
    $nombrer='A. '.$recibe;
}
$pdf->SetY(98);
$pdf->SetX(120);
$pdf->SetFont('Arial','',12);
$pdf->Cell(80,4,utf8_decode($nombrer),0,0,'C',0);


//
$pdf->SetY(104);
$pdf->SetX(8);
$pdf->SetFont('Arial','',10);
$pdf->Cell(200,4,utf8_decode('Xontepec No. 105 Col. Toriello Guerra, Tlalpan, C.P. 14050 CDMX'),0,0,'C',0);
//
$pdf->SetY(110);
$pdf->SetX(8);
$pdf->Cell(200,4,utf8_decode('Tels.: 55-5606-8916 55-5666-9428 R.F.C. VVA920903N10'),0,0,'C',0);

//segundo recibo
//marco del recibo
$pdf->SetFillColor(000,000,000);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(4+130);
$pdf->SetX(4);
$pdf->Cell(208,112,'',1,0,'L', FALSE, 1);
//logo 
$pdf->Image('../images/logo.png',140,138,70);
//Titulo
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(000,000,000);
$pdf->SetY(138);
$pdf->SetX(8);
$pdf->Cell(60,4,utf8_decode('FORMATO DE GASTOS MÉDICOS Y REEMBOLSOS'),0,0,'L',0);
//
$pdf->SetY(16+130);
$pdf->SetX(8);
$pdf->Cell(60,4,'Folio: ',0,0,'L',0);
//
$pdf->SetTextColor(255,000,000);
$pdf->SetX(28);
$pdf->Cell(20,4,$ResRe["Id"],'B',0,'L',0);
//
$pdf->SetTextColor(000,000,000);
$pdf->Cell(20,4,'Fecha: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(60,4,fecha($ResRe["Fecha"]),'B',0,'L',0);
//
$pdf->SetY(24+130);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(23,4,'Registro: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(17,4,$ResRe["IdPaciente"],'B',0,'L',0);
//
$pdf->SetFont('Arial','B',14);
$pdf->Cell(20,4,'Ayuda: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(17,4,$ResRe["TipoAyuda"].' %','B',0,'L',0);
//
$pdf->SetY(32+130);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(23,4,'Paciente: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(105,4,utf8_decode($ResPac["Nombre"].' '.$ResPac["Apellidos"]),'B',0,'L',0);
//
$pdf->SetY(40+130);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(23,4,'Recibe: ',0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(105,4,utf8_decode($recibe),'B',0,'L',0);
//
$pdf->SetY(48+130);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(40,4,utf8_decode('La cantidad de : '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$cantidad=explode('.', $ResRe["CantidadEntregada"]);
$pdf->Cell(160,4,'$ '.number_format($cantidad[0], 2).' ('.num2letras($cantidad[0]).' pesos '.$cantidad[1].'/100 M.N.)','B',0,'L',0);
//
$pdf->SetY(56+130);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(43,4,utf8_decode('Por concepto de: '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(157,4,utf8_decode($ResRe["Concepto"]),'B',0,'L',0);
//
$pdf->SetY(64+130);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(28,4,utf8_decode('Proveedor: '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$pdf->Cell(173,4,utf8_decode($ResRe["Provedor"]),'B',0,'L',0);
//
$pdf->SetY(72+130);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(35,4,utf8_decode('Num. Factura: '),0,0,'L',0);
$pdf->SetFont('Arial','',14);
$pdf->Cell(28,4,utf8_decode($ResRe["NumFactura"]),'B',0,'L',0);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(37,4,utf8_decode('Monto Factura: '),0,0,'L',0);
//
$pdf->SetFont('Arial','',14);
$cantidadf=explode('.', $ResRe["CantidadFactura"]);
$pdf->Cell(100,4,'$ '.number_format($cantidadf[0], 2).' ('.num2letras($cantidadf[0]).' pesos '.$cantidadf[1].'/100 M.N.)','B',0,'L',0);
//
//autoriza
$pdf->SetY(94+130);
$pdf->SetX(20);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(80,4,utf8_decode('Autoriza'),'T',0,'C',0);
//
$ResAutorizo=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM usuarios WHERE Id='".$ResRe["Autorizo"]."' LIMIT 1"));
$pdf->SetY(98+130);
$pdf->SetX(20);
$pdf->SetFont('Arial','',12);
$pdf->Cell(80,4,utf8_decode($ResAutorizo["Nombre"]),0,0,'C',0);
//recibio
$pdf->SetY(94+130);
$pdf->SetX(120);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(80,4,utf8_decode('Recibio'),'T',0,'C',0);
//
if ($ResRe["PA"]=='P')
{
    $nombrer='P. '.$recibe;
}
elseif($ResRe["PA"]=='A')
{
    $nombrer='A. '.$recibe;
}
$pdf->SetY(98+130);
$pdf->SetX(120);
$pdf->SetFont('Arial','',12);
$pdf->Cell(80,4,utf8_decode($nombrer),0,0,'C',0);


//
$pdf->SetY(104+130);
$pdf->SetX(8);
$pdf->SetFont('Arial','',10);
$pdf->Cell(200,4,utf8_decode('Xontepec No. 105 Col. Toriello Guerra, Tlalpan, C.P. 14050 CDMX'),0,0,'C',0);
//
$pdf->SetY(110+130);
$pdf->SetX(8);
$pdf->Cell(200,4,utf8_decode('Tels.: 55-5606-8916 55-5666-9428 R.F.C. VVA920903N10'),0,0,'C',0);


//Envio Archivo
$pdf->Output();