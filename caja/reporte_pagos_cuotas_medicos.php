<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

require('../libs/fpdf/fpdf.php');

//crear el nuevo archivo pdf
$pdf=new FPDF('P', 'mm', 'Letter');

//desabilitar el corte automatico de pagina
$pdf->SetAutoPageBreak(false); $J=1;

$ResRecibos=mysqli_query($conn, "SELECT * FROM pagogastosmedicos WHERE Fecha>='".$_GET["fechaini"]."' AND Fecha<='".$_GET["fechafin"]."' ORDER BY Id DESC");

$J=1; $T=1;
while($RResRec=mysqli_fetch_array($ResRecibos))
{
    $ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$RResRec["IdPaciente"]."'"));

    if($T==1)
    {
        //Agregamos la primer pagina
        $pdf->AddPage();

        //titulo
        $pdf->SetY(16);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(130,4,'REPORTE DE CUOTAS GASTOS MEDICOS',0,0,'C',0);
        //
        $pdf->SetY(24);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(130,4,'Periodo del : '.fechados($_GET["fechaini"]).' al '.fechados($_GET["fechafin"]),0,0,'C',0);
        //logo 
        $pdf->Image('../images/logo.png',140,8,70);

        $y_axis_initial=42;
        $y_axis=48;

        $pdf->SetFillColor(057,044,138);
        $pdf->SetFont('Arial','B',8);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetY($y_axis_initial);
        $pdf->SetX(8);
        $pdf->Cell(15,6,'#',1,0,'C',1);
        $pdf->Cell(20,6,utf8_decode('No Recibo'),1,0,'C',1);
        $pdf->Cell(20,6,'Fecha',1,0,'C',1);
        $pdf->Cell(20,6,'No Paciente',1,0,'C',1);
        $pdf->Cell(105,6,'Paciente',1,0,'C',1);
        $pdf->Cell(20,6,'Importe',1,0,'C',1);
    }
    
    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial','',8);
    $pdf->SetTextColor(000,000,000);
    $pdf->SetY($y_axis);
    $pdf->SetX(8);
    $pdf->Cell(15,6,$J,1,0,'C',1);
    $pdf->Cell(20,6,$RResRec["Id"],1,0,'C',1);
    $pdf->Cell(20,6,fechados($RResRec["Fecha"]),1,0,'C',1);
    $pdf->Cell(20,6,$ResPac["Id"],1,0,'C',1);
    $pdf->Cell(105,6,utf8_decode($ResPac["Nombre"].' '.$ResPac["Apellidos"]),1,0,'C',1);
    $pdf->Cell(20,6,'$ '.number_format($RResRec["CantidadEntregada"],2),1,0,'R',1);

    $y_axis=$y_axis+6;

    $J++; $T++;

    if($T==39){$T=1;}
}

$ResTotal=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CantidadEntregada) AS TotMonto FROM pagogastosmedicos WHERE Fecha>='".$_GET["fechaini"]."' AND Fecha<='".$_GET["fechafin"]."'"));
$pdf->SetY($y_axis);
$pdf->SetX(8);
$pdf->Cell(180,6,'Total: ',1,0,'R',1);
$pdf->Cell(20,6,'$ '.number_format($ResTotal["TotMonto"],2),1,0,'R',1);

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '126', '".json_encode($_GET)."')");

//Envio Archivo
$pdf->Output();

