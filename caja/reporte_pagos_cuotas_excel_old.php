<?php 
//Inicio la sesion 
session_start();
include('../conexion.php');
include ("excelgen.class.php");

//initiate a instance of "excelgen" class
$excel = new ExcelGen("ReporteCuotas");

//initiate $row,$col variables
$row=0;
$col=0;

$excel->WriteText($row,$col,"Cobrado Por");$col++;
$excel->WriteText($row,$col,"N. Recibo");$col++;
$excel->WriteText($row,$col,"Fecha");$col++;
$excel->WriteText($row,$col,"N. Paciente");$col++;
$excel->WriteText($row,$col,"Paciente");$col++;
$excel->WriteText($row,$col,"Reservación");$col++;
$excel->WriteText($row,$col,"Monto");$col++;

$row++;
$col=0;

$ResRecibos=mysqli_query($conn, "SELECT * FROM pagoreservacion WHERE Fecha>='".$_GET["fechaini"]."' AND Fecha<='".$_GET["fechafin"]."' ORDER BY Id DESC");
while($RResRec=mysqli_fetch_array($ResRecibos))
{
    $ResPaciente=mysqli_fetch_array(mysqli_query($conn, "SELECT p.Id AS IdP, concat_ws(' ', p.Nombre, p.Apellidos) AS NombrePaciente FROM reservacion AS r 
                                            INNER JOIN pacientes AS p ON r.IdPaciente=p.Id 
                                            WHERE r.Id='".$RResRec["IdReservacion"]."'")); 

	$ResUsu=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM usuarios WHERE Id='".$RResRec["Usuario"]."' LIMIT 1"));
        
        $excel->WriteText($row,$col,$ResUsu["Nombre"]);$col++;
        $excel->WriteText($row,$col,$RResRec["Id"]);$col++;
		$excel->WriteText($row,$col,$RResRec["Fecha"]);$col++;
		$excel->WriteText($row,$col,$ResPaciente["IdP"]);$col++;
		$excel->WriteText($row,$col,$ResPaciente["NombrePaciente"]);$col++;
		$excel->WriteText($row,$col,$RResRec["IdReservacion"]);$col++;
		$excel->WriteNumber($row,$col,$RResRec["Monto"]);$col++;

        $row++;
		$col=0;
}

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '110', '".json_encode($_GET)."')");

//stream Excel for user to download or show on browser
$excel->SendFile();
?>


