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

$ResProductos=mysqli_query($conn, "SELECT * FROM productos ORDER BY Nombre ASC");
while($RResP=mysqli_fetch_array($ResProductos))
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
        $pdf->Cell(80,6,'Producto',1,0,'C',1);
        $pdf->Cell(58,6,utf8_decode('Presentación'),1,0,'C',1);
        $pdf->Cell(50,6,'Almacen',1,0,'C',1);
        $pdf->Cell(20,6,'Stock',1,0,'C',1);
        
        $Y=32;
    }

    //productos
    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(000,000,000);

    $ResPr=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM presentaciones WHERE Id='".$RResP["Presentacion"]."' LIMIT 1"));

    $ResAlmacenes=mysqli_query($conn, "SELECT * FROM almacenes ORDER BY Nombre ASC");
    while($RResA=mysqli_fetch_array($ResAlmacenes))
    {
        $ResInventario=mysqli_query($conn, "SELECT Stock FROM productos_inventario WHERE IdProducto='".$RResP["Id"]."' AND IdAlmacen='".$RResA["Id"]."' AND Stock > 0 ORDER BY Fecha DESC, Id DESC LIMIT 1");
        if(mysqli_num_rows($ResInventario)>0)
        {
            while($RResI=mysqli_fetch_array($ResInventario))
            {
                $pdf->SetY($Y);
                $pdf->SetX(4);
                $pdf->Cell(80,6, utf8_decode(strtoupper(utf8_encode($RResP["Nombre"])).' - '.$RResP["Volumen"]),1,0,'L',1);
                $pdf->Cell(58,6, $ResPr["Nombre"].$K,1,0,'C',1);
                $pdf->Cell(50,6, $RResA["Nombre"],1,0,'C',1);
                $pdf->Cell(20,6, $RResI["Stock"],1,0,'C',1);


                $Y=$Y+6; $K++;
            }
        }
    }

    
}


//Envio Archivo
$pdf->Output();

