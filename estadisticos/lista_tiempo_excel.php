<?php 
//Inicio la sesion 
session_start();
include('../conexion.php');
include('../funciones.php');
include ("../caja/excelgen.class.php");

$fechai=$_GET["fechai"];
$fechaf=$_GET["fechaf"];

//initiate a instance of "excelgen" class
$excel = new ExcelGen("ReportePersonas");

//initiate $row,$col variables
$row=0;
$col=0;

$excel->WriteText($row,$col,"Numero");$col++;
$excel->WriteText($row,$col,"Tipo");$col++;
$excel->WriteText($row,$col,"Registro");$col++;
$excel->WriteText($row,$col,"Nombre");$col++;
$excel->WriteText($row,$col,"Sexo");$col++;
$excel->WriteText($row,$col,"Fecha de Nacimiento");$col++;
$excel->WriteText($row,$col,"CURP");$col++;
$excel->WriteText($row,$col,"Edad");$col++;
$excel->WriteText($row,$col,"Talla");$col++;
$excel->WriteText($row,$col,"Peso");$col++;
$excel->WriteText($row,$col,"Estado");$col++;
$excel->WriteText($row,$col,"Municipio");$col++;
$excel->WriteText($row,$col,"Direccion");$col++;
$excel->WriteText($row,$col,"Hospital");$col++;
$excel->WriteText($row,$col,"Carnet");$col++;

$row++;
$col=0;

$ResPersonas=mysqli_query($conn, "SELECT concat_ws('-', r.IdPA, r.Tipo) AS idpa FROM `reservaciones` AS r 
                                    WHERE Fecha>='".$fechai."' AND Fecha<='".$fechaf."' AND r.Estatus=1 GROUP BY concat_ws('-', r.IdPA, r.Tipo)");

$J=1;
while($ResP=mysqli_fetch_array($ResPersonas))
{
    $p=explode('-', $ResP["idpa"]);
    $excel->WriteText($row,$col,$J);$col++;
    $excel->WriteText($row,$col,$p[1]);$col++;
    $excel->WriteText($row,$col,$p[0]);$col++;

    if($p[1]=='P')
    {
        $ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pacientes WHERE Id='".$p[0]."' LIMIT 1"));

        if($ResPac["FechaNacimiento"]!=NULL)
        {
            $fecha_nac = new DateTime(date('Y/m/d',strtotime($ResPac["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
            $fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
            $cedad = date_diff($fecha_hoy,$fecha_nac);
            $edad=$cedad->format('%Y').' años '.$cedad->format('%m').' meses';
        }
        else
        {
            $edad='---';
        }

        $ResEstado=mysqli_fetch_array(mysqli_query($conn, "SELECT Estado FROM Estados WHERE Id='".$ResPac["Estado"]."' LIMIT 1"));
        $ResMunicipio=mysqli_fetch_array(mysqli_query($conn, "SELECT Municipio FROM municipios WHERE Id='".$ResPac["Municipio"]."' LIMIT 1"));
        $ResHospital=mysqli_fetch_array(mysqli_query($conn, "SELECT Instituto FROM institutos WHERE Id='".$ResPac["Instituto1"]."' LIMIT 1"));

        $excel->WriteText($row,$col,$ResPac["Nombre"].' '.$ResPac["Apellidos"]);$col++;
        $excel->WriteText($row,$col,$ResPac["Sexo"]);$col++;
        $excel->WriteText($row,$col,fechados($ResPac["FechaNacimiento"]));$col++;
        $excel->WriteText($row,$col,$ResPac["Curp"]);$col++;
        $excel->WriteText($row,$col,utf8_decode($edad));$col++;
        $excel->WriteText($row,$col,$ResPac["Talla"]);$col++;
        $excel->WriteText($row,$col,$ResPac["Peso"]);$col++;
        $excel->WriteText($row,$col,$ResEstado["Estado"]);$col++;
        $excel->WriteText($row,$col,$ResMunicipio["Municipio"]);$col++;
        $excel->WriteText($row,$col,$ResPac["Domicilio"]);$col++;
        $excel->WriteText($row,$col,$ResHospital["Instituto"]);$col++;
        $excel->WriteText($row,$col,$ResPac["Carnet1"]);$col++;
    }
    elseif($p[1]=='A')
    {
        $ResAco=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM acompannantes  WHERE Id='".$p[0]."' LIMIT 1"));

        if($ResAco["FechaNacimiento"]!=NULL)
        {
            $fecha_nac = new DateTime(date('Y/m/d',strtotime($ResPac["FechaNacimiento"]))); // Creo un objeto DateTime de la fecha ingresada
            $fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
            $cedad = date_diff($fecha_hoy,$fecha_nac);
            $edad=$cedad->format('%Y').' años '.$cedad->format('%m').' meses';
        }
        else
        {
            $edad='---';
        }

        $ResEstado=mysqli_fetch_array(mysqli_query($conn, "SELECT Estado FROM Estados WHERE Id='".$ResAco["Estado"]."' LIMIT 1"));
        $ResMunicipio=mysqli_fetch_array(mysqli_query($conn, "SELECT Municipio FROM municipios WHERE Id='".$ResAco["Municipio"]."' LIMIT 1"));

        $excel->WriteText($row,$col,$ResAco["Nombre"]);$col++;
        $excel->WriteText($row,$col,$ResAco["Sexo"]);$col++;
        $excel->WriteText($row,$col,fechados($ResAco["FechaNacimiento"]));$col++;
        $excel->WriteText($row,$col,$ResAco["Curp"]);$col++;
        $excel->WriteText($row,$col,utf8_decode($edad));$col++;
        $excel->WriteText($row,$col,$ResAco["Talla"]);$col++;
        $excel->WriteText($row,$col,$ResAco["Peso"]);$col++;
        $excel->WriteText($row,$col,$ResEstado["Estado"]);$col++;
        $excel->WriteText($row,$col,$ResMunicipio["Municipio"]);$col++;
        $excel->WriteText($row,$col,$ResAco["Domicilio"]);$col++;

    }

    $row++;
	$col=0;
    $J++;
}


//stream Excel for user to download or show on browser
$excel->SendFile();
?>