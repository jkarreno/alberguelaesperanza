<?php

require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');

// Creamos el grafico
$datos=array(6,5,8,6);
$labels=array("pepe","juanita","Maria","Luis");

$grafico = new Graph(500, 400, 'auto');
$grafico->SetScale("textlin");
$grafico->title->Set("Ejemplo de Grafica");
$grafico->xaxis->title->Set("trabajadores");
$grafico->xaxis->SetTickLabels($labels);
$grafico->yaxis->title->Set("Horas Trabajadas");

$barplot1 =new BarPlot($datos);
$barplot1->SetWidth(30); // 30 pixeles de ancho para cada barra

$grafico->Add($barplot1);
$grafico->Stroke();

?>