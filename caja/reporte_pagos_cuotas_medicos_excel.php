<?php 
//Inicio la sesion 
session_start();
include('../conexion.php');
include('../funciones.php');

require '../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$excel = new SpreadSheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva ->setTitle("ReporteCuotasMedicos");

$hojaActiva->setCellValue('A1', 'N. Recibo');
$hojaActiva->setCellValue('B1', 'Fecha');
$hojaActiva->setCellValue('C1', 'N. Paciente');
$hojaActiva->setCellValue('D1', 'Paciente');
$hojaActiva->setCellValue('E1', 'Concepto');
$hojaActiva->setCellValue('F1', 'Monto');

$fila=2;

$ResRecibos=mysqli_query($conn, "SELECT * FROM pagogastosmedicos WHERE Fecha>='".$_GET["fechaini"]."' AND Fecha<='".$_GET["fechafin"]."' ORDER BY Id DESC");
while($RResRec=mysqli_fetch_array($ResRecibos))
{
    $ResPaciente=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2 FROM pacientes WHERE Id='".$RResRec["IdPaciente"]."'"));

    $hojaActiva->setCellValue('A'.$fila, $RResRec["Id"]);
    $hojaActiva->setCellValue('B'.$fila, $RResRec["Fecha"]);
    $hojaActiva->setCellValue('C'.$fila, $ResPaciente["Id"]);
    $hojaActiva->setCellValue('D'.$fila, $ResPaciente["Nombre"].' '.$ResPaciente["Apellidos"].' '.$ResPaciente["Apellidos2"]);
    $hojaActiva->setCellValue('E'.$fila, $RResRec["Concepto"]);
    $hojaActiva->setCellValue('F'.$fila, $RResRec["CantidadEntregada"]);

    $fila++;
}

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '127', '".json_encode($_GET)."')");

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReporteCuotasMedicos.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;