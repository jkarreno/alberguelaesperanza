<?php
header('Content-type: image/jpeg');
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

require_once ('../../jpgraph/jpgraph.php');
require_once ('../../jpgraph/jpgraph_bar.php');

$anno=$_GET["anno"];
$mes=$_GET["mes"];

//calcula datos

//total de ocupaciones
$ResOcu=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Estatus=1 ORDER BY Fecha DESC");
$TOcupaciones=mysqli_num_rows($ResOcu);
//total por confirmar
$ResCon=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Estatus=0 ORDER BY Fecha DESC");
$TpConfirmar=mysqli_num_rows($ResCon);
//total cancelaciones
$ResCan=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha LIKE '".$anno."-".$mes."-%' AND Estatus=2 ORDER BY Fecha DESC");
$TCancelaciones=mysqli_num_rows($ResCan);

// Creamos el grafico
$datos1=array($TOcupaciones, 0, 0);
$datos2=array(0, $TpConfirmar, 0);
$datos3=array(0, 0, $TCancelaciones);
$labels=array("Ocupaciones","Por Confirmar","Canceladas");

$grafico = new Graph(800, 600, 'auto');
$grafico->SetScale("textlin");
$grafico->title->Set("Reservaciones");
$grafico->xaxis->SetTickLabels($labels);

// Create the bar plots
$b1plot = new BarPlot($datos1);
$b2plot = new BarPlot($datos2);
$b3plot = new BarPlot($datos3);

// Create the grouped bar plot
//$gbplot = new GroupBarPlot(array($b1plot,$b2plot,$b3plot));
// ...and add it to the grafico
$grafico->Add(array($b1plot, $b2plot, $b3plot));


$b1plot->SetColor("white");
$b1plot->SetFillColor("#26b719");
$b1plot->SetWidth(200); 

$b2plot->SetColor("white");
$b2plot->SetFillColor("#0864bf");
$b2plot->SetWidth(200); 

$b3plot->SetColor("white");
$b3plot->SetFillColor("#ff0000");
$b3plot->SetWidth(200); 

$nombreImagen = 'img/' . uniqid() . '.png';

// Display the grafico
$grafico->Stroke($nombreImagen);

?>