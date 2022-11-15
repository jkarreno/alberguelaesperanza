<?php 
//Inicio la sesion 
session_start();
include('../conexion.php');
include('../funciones.php');

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$ResProductos=mysqli_query($conn, "SELECT * FROM productos ORDER BY Nombre ASC");

$excel = new SpreadSheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva ->setTitle("ExistenciasAlmacen");

$hojaActiva->setCellValue('A1', 'Producto');
$hojaActiva->setCellValue('B1', 'Presentación');
$hojaActiva->setCellValue('C1', 'Almacén');
$hojaActiva->setCellValue('D1', 'Existencia');

$fila=2;

while($RResP=mysqli_fetch_array($ResProductos))
{
    $ResPr=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM presentaciones WHERE Id='".$RResP["Presentacion"]."' LIMIT 1"));

    $ResAlmacenes=mysqli_query($conn, "SELECT * FROM almacenes ORDER BY Nombre ASC");
    while($RResA=mysqli_fetch_array($ResAlmacenes))
    {
        $ResInventario=mysqli_query($conn, "SELECT Stock FROM productos_inventario WHERE IdProducto='".$RResP["Id"]."' AND IdAlmacen='".$RResA["Id"]."' AND Stock > 0 ORDER BY Fecha DESC, Id DESC LIMIT 1");
        if(mysqli_num_rows($ResInventario)>0)
        {
            while($RResI=mysqli_fetch_array($ResInventario))
            {
                $hojaActiva->setCellValue('A'.$fila, strtoupper(utf8_encode($RResP["Nombre"])).' - '.$RResP["Volumen"]);
                $hojaActiva->setCellValue('B'.$fila, $ResPr["Nombre"]);
                $hojaActiva->setCellValue('C'.$fila, $RResA["Nombre"]);
                $hojaActiva->setCellValue('D'.$fila, $RResI["Stock"]);


                $fila++;
            }
        }
    }
}

/* Here there will be some code where you create $spreadsheet */

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ExistenciasAlmacen.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;