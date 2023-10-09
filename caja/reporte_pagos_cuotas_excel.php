<?php 
//Inicio la sesion 
session_start();
include('../conexion.php');
include('../funciones.php');

require '../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$ResRecibos=mysqli_query($conn, "SELECT * FROM pagoreservacion WHERE Fecha>='".$_GET["fechaini"]."' AND Fecha<='".$_GET["fechafin"]."' ORDER BY Id DESC");

$excel = new SpreadSheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva ->setTitle("ReporteCuotas");

$hojaActiva->setCellValue('A1', 'Cobrado Por');
$hojaActiva->setCellValue('B1', 'N. Recibo');
$hojaActiva->setCellValue('C1', 'Fecha');
$hojaActiva->setCellValue('D1', 'N. Paciente');
$hojaActiva->setCellValue('E1', 'Paciente');
$hojaActiva->setCellValue('F1', 'Reservación');
$hojaActiva->setCellValue('G1', 'Días Paciente');
$hojaActiva->setCellValue('H1', 'Días Acompañante');
$hojaActiva->setCellValue('I1', 'Monto');

$fila=2;

while($RResRec=mysqli_fetch_array($ResRecibos))
{
    $ResPaciente=mysqli_fetch_array(mysqli_query($conn, "SELECT p.Id AS IdP, concat_ws(' ', p.Nombre, p.Apellidos) AS NombrePaciente, r.Dias AS Dias FROM reservacion AS r 
                                            INNER JOIN pacientes AS p ON r.IdPaciente=p.Id 
                                            WHERE r.Id='".$RResRec["IdReservacion"]."'")); 

    if($RResRec["Estatus"]==0){$RResRec["Monto"]=0;}

	$ResUsu=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM usuarios WHERE Id='".$RResRec["Usuario"]."' LIMIT 1"));

    //dias paciente
    $ResDP=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS Dias FROM reservaciones WHERE IdReservacion='".$RResRec["IdReservacion"]."' AND Tipo='P' AND Cama>0"));

    //dias acompañante
    $ResDA=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS Dias FROM reservaciones WHERE IdReservacion='".$RResRec["IdReservacion"]."' AND Tipo='A' AND Cama>0"));

    $hojaActiva->setCellValue('A'.$fila, $ResUsu["Nombre"]);
    $hojaActiva->setCellValue('B'.$fila, $RResRec["Id"]);
    $hojaActiva->setCellValue('C'.$fila, $RResRec["Fecha"]);
    $hojaActiva->setCellValue('D'.$fila, $ResPaciente["IdP"]);
    $hojaActiva->setCellValue('E'.$fila, $ResPaciente["NombrePaciente"]);
    $hojaActiva->setCellValue('F'.$fila, $RResRec["IdReservacion"]);
    $hojaActiva->setCellValue('G'.$fila, $ResDP["Dias"]);
    $hojaActiva->setCellValue('H'.$fila, $ResDA["Dias"]);
    $hojaActiva->setCellValue('I'.$fila, $RResRec["Monto"]);

    $fila++;
}

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '110', '".json_encode($_GET)."')");

/* Here there will be some code where you create $spreadsheet */

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReporteCuotas.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;