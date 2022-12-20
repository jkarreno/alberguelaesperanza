<?php 
//Inicio la sesion 
session_start();
include('../conexion.php');
include('../funciones.php');

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$ResProducto=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre FROM productos WHERE Id='".$_GET["producto"]."' LIMIT 1"));

$excel = new SpreadSheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva ->setTitle("Existencias ".$ResProducto["Nombre"]);

$hojaActiva->setCellValue('A1', 'Producto');
$hojaActiva->setCellValue('B1', 'Almacén');
$hojaActiva->setCellValue('C1', 'Presentación');
$hojaActiva->setCellValue('D1', 'Movimiento');
$hojaActiva->setCellValue('E1', 'Cantidad');
$hojaActiva->setCellValue('F1', 'Stock');
$hojaActiva->setCellValue('G1', 'Caducidad');
$hojaActiva->setCellValue('H1', 'Observaciones');
$hojaActiva->setCellValue('I1', 'Fecha');

$fila=2; $col='A';

$ResStock=mysqli_query($conn, "SELECT * FROM productos_inventario WHERE IdProducto='".$ResProducto["Id"]."' AND IdAlmacen LIKE '".$_GET["almacen"]."' ORDER BY Fecha DESC, Id DESC LIMIT 100");
$J=1; 
while($RResS=mysqli_fetch_array($ResStock))
{
    $ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM productos WHERE Id='".$RResS["IdProducto"]."' LIMIT 1"));
    $ResA=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM almacenes WHERE Id='".$RResS["IdAlmacen"]."' LIMIT 1"));
    $ResU=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM presentaciones WHERE Id='".$ResP["Presentacion"]."' LIMIT 1"));

    switch($RResS["Movimiento"])
    {
        case 'I': $mov='Inventario'; 
        case 'E': $mov='Entrada';
        case 'S': $mov='Salida'; 
    }

    if($RResS["Caducidad"]=='1900-01-01'){$caducidad='---';}
    else{$caducidad=caducidad($RResS["Caducidad"]);}

    $hojaActiva->setCellValue($col.$fila, $J);$col++;
    $hojaActiva->setCellValue($col.$fila, strtoupper(utf8_encode($ResP["Nombre"])).' - '.$ResP["Volumen"]);$col++;
    $hojaActiva->setCellValue($col.$fila, $ResA["Nombre"]);$col++;
    $hojaActiva->setCellValue($col.$fila, $ResU["Nombre"]);$col++;
    $hojaActiva->setCellValue($col.$fila, $mov);$col++;
    $hojaActiva->setCellValue($col.$fila, $RResS["Cantidad"]);$col++;
    $hojaActiva->setCellValue($col.$fila, $RResS["Stock"]);$col++;
    $hojaActiva->setCellValue($col.$fila, $caducidad);$col++;
    $hojaActiva->setCellValue($col.$fila, $RResS["Observaciones"]);$col++;
    $hojaActiva->setCellValue($col.$fila, fechados($RResS["Fecha"]));$col++;

    $J++;
    $fila++;
    $col='A';
}

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ExistenciaProducto.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
