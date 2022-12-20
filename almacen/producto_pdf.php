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
//$pdf->AddPage('P', 'Letter');

$K=40;

$ResProducto=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre FROM productos WHERE Id='".$_GET["producto"]."' LIMIT 1"));

$ResStock=mysqli_query($conn, "SELECT * FROM productos_inventario WHERE IdProducto='".$ResProducto["Id"]."' AND IdAlmacen LIKE '".$_GET["almacen"]."' ORDER BY Fecha DESC, Id DESC LIMIT 100");
while($RResS=mysqli_fetch_array($ResStock))
{
    if($K==40) 
    {
        $K=1; 
        
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
        //encabezados
        $pdf->SetFillColor(204,204,204);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetTextColor(000,000,000);
        $pdf->SetY(26);
        $pdf->SetX(4);
        $pdf->Cell(25,6,utf8_decode('Almacén'),1,0,'C',1);
        $pdf->Cell(30,6,utf8_decode('Presentación'),1,0,'C',1);
        $pdf->Cell(25,6,'Movimiento',1,0,'C',1);
        $pdf->Cell(20,6,'Cantidad',1,0,'C',1);
        $pdf->Cell(20,6,'Stock',1,0,'C',1);
        $pdf->Cell(25,6,'Caducidad',1,0,'C',1);
        $pdf->Cell(38,6,'Observaciones',1,0,'C',1);
        $pdf->Cell(25,6,'Fecha',1,0,'C',1);
        
        $Y=32;
    }

    $ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM productos WHERE Id='".$RResS["IdProducto"]."' LIMIT 1"));
    $ResA=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM almacenes WHERE Id='".$RResS["IdAlmacen"]."' LIMIT 1"));
    $ResU=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM presentaciones WHERE Id='".$ResP["Presentacion"]."' LIMIT 1"));

    switch($RResS["Movimiento"])
    {
        case 'I': $mov='Inventario'; break;
        case 'E': $mov='Entrada'; break;
        case 'S': $mov='Salida'; break;
    }

    if($RResS["Caducidad"]=='1900-01-01'){$caducidad='---';}
    else{$caducidad=caducidad($RResS["Caducidad"]);}

    $pdf->SetY($Y);
    $pdf->SetX(4);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(000,000,000);
    $pdf->Cell(25,6, $ResA["Nombre"],1,0,'L',1);
    $pdf->Cell(30,6, $ResU["Nombre"],1,0,'C',1);
    $pdf->Cell(25,6, $mov,1,0,'C',1);
    $pdf->Cell(20,6, $RResS["Cantidad"],1,0,'C',1);
    $pdf->Cell(20,6, $RResS["Stock"],1,0,'C',1);
    $pdf->Cell(25,6, $caducidad,1,0,'C',1);
    $pdf->Cell(38,6, $RResS["Observaciones"],1,0,'C',1);
    $pdf->Cell(25,6, fechados($RResS["Fecha"]),1,0,'C',1);


    $Y=$Y+6; $K++;

}


//Envio Archivo
$pdf->Output();