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

$ResRecibos=mysqli_query($conn, "SELECT * FROM pagoreservacion WHERE Fecha>='".$_GET["fechaini"]."' AND Fecha<='".$_GET["fechafin"]."' ORDER BY Id DESC");

$J=1; $T=1;
while($RResRec=mysqli_fetch_array($ResRecibos))
{
    $ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT p.Id AS pId, p.Nombre AS Nombre, p.Apellidos AS Apellidos, r.Dias AS Dias FROM reservacion AS r 
                                                    INNER JOIN pacientes AS p ON p.Id=r.IdPaciente 
                                                    WHERE r.Id='".$RResRec["IdReservacion"]."'"));

    if($RResRec["Estatus"]==0){$RResRec["Monto"]=0;}

    $ResUsu=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM usuarios WHERE Id='".$RResRec["Usuario"]."' LIMIT 1"));

    //dias paciente
    $ResDP=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS Dias FROM reservaciones WHERE IdReservacion='".$RResRec["IdReservacion"]."' AND Tipo='P' AND Cama>0"));

    //dias acompañante
    $ResDA=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS Dias FROM reservaciones WHERE IdReservacion='".$RResRec["IdReservacion"]."' AND Tipo='A' AND Cama>0"));

    if($T==1)
    {
        //Agregamos la primer pagina
        $pdf->AddPage();

        //titulo
        $pdf->SetY(16);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(130,4,'REPORTE DE CUOTAS',0,0,'C',0);
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
        $pdf->SetX(4);
        $pdf->Cell(10,6,'#',1,0,'C',1);
        $pdf->Cell(15,6,utf8_decode('Recibo'),1,0,'C',1);
        $pdf->Cell(35,6,utf8_decode('Recibió'),1,0,'C',1);
        $pdf->Cell(20,6,'Fecha',1,0,'C',1);
        $pdf->Cell(12,6,'No',1,0,'C',1);
        $pdf->Cell(50,6,'Paciente',1,0,'C',1);
        $pdf->Cell(20,6,utf8_decode('Reservación'),1,0,'C',1);
        $pdf->Cell(15,6,utf8_decode('Días P'),1,0,'C',1);
        $pdf->Cell(15,6,utf8_decode('Días A'),1,0,'C',1);
        $pdf->Cell(15,6,'Importe',1,0,'C',1);
    }
    
    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial','',8);
    $pdf->SetTextColor(000,000,000);
    $pdf->SetY($y_axis);
    $pdf->SetX(4);
    $pdf->Cell(10,6,$J,1,0,'C',1);
    $pdf->Cell(15,6,$RResRec["Id"],1,0,'C',1);
    $pdf->Cell(35,6,$ResUsu["Nombre"],1,0,'L',1);
    $pdf->Cell(20,6,fechados($RResRec["Fecha"]),1,0,'C',1);
    $pdf->Cell(12,6,$ResPac["pId"],1,0,'C',1);
    $pdf->Cell(50,6,utf8_decode($ResPac["Nombre"].' '.$ResPac["Apellidos"]),1,0,'L',1);
    $pdf->Cell(20,6,$RResRec["IdReservacion"],1,0,'C',1);
    $pdf->Cell(15,6,$ResDP["Dias"],1,0,'C',1);
    $pdf->Cell(15,6,$ResDA["Dias"],1,0,'C',1);
    $pdf->Cell(15,6,'$ '.number_format($RResRec["Monto"],2),1,0,'R',1);

    $y_axis=$y_axis+6;

    $J++; $T++;

    if($T==39){$T=1;}
}

$ResTotal=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Monto) AS TotMonto FROM pagoreservacion WHERE Fecha>='".$_GET["fechaini"]."' AND Fecha<='".$_GET["fechafin"]."' AND Estatus!='0'"));
$pdf->SetY($y_axis);
$pdf->SetX(4);
$pdf->Cell(192,6,'Total: ',1,0,'R',1);
$pdf->Cell(15,6,'$ '.number_format($ResTotal["TotMonto"],2),1,0,'R',1);

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '111', '".json_encode($_GET)."')");

//Envio Archivo
$pdf->Output();

