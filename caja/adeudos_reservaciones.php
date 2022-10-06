<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$cadena='<table style="width:80%">
            <thead>
                <tr>
                    <th colspan="8" align="center" class="textotitable">Reservaciones con adeudos</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th align="center" class="textotitable">Reservación</th>
                    <th align="center" class="textotitable">Fecha</th>
                    <th align="center" class="textotitable">Registro</th>
                    <th align="center" class="textotitable">Paciente</th>
                    <th align="center" class="textotitable">Monto</th>
                    <th align="center" class="textotitable">Pagado</th>
                    <th align="center" class="textotitable">Adeudo</th>
                    <th align="center" class="textotitable"></th>
                <tr>';
$ResReservaciones=mysqli_query($conn, "SELECT * FROM reservacion WHERE Pagada<=1 AND Estatus=1 ORDER BY Id DESC");
$J=1; $bgcolor="#ffffff";
while($RResR=mysqli_fetch_array($ResReservaciones))
{
    $ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT CONCAT(Nombre, ' ', Apellidos, ' ', Apellidos2) AS Nombre FROM pacientes WHERE Id='".$RResR["IdPaciente"]."' LIMIT 1"));

    $ResPagado=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Monto) AS Total FROM pagoreservacion WHERE IdREservacion='".$RResR["Id"]));

    $monto=monto_reservacion($RResR["Id"]);

    $cadena.='  <tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$RResR["Id"].'</td>
                    <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$RResR["Fecha"].'</td>
                    <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$RResR["IdPaciente"].'</td>
                    <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">'.$ResP["Nombre"].'</td>
                    <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="right" class="texto">'.number_format($monto, 2).'</td>
                    <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="right" class="texto">'.number_format($ResPagado["Total"], 2).'</td>
                    <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="right" class="texto">'.number_format(($monto-$ResPagado["Total"]), 2).'</td>
                    <td width="100" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto"><i style="cursor: pointer; color: #ff0000;"  class="fas fa-cash-register" '.permisos(9, 'onclick="limpiar; abrirmodal(); pago_reservacion(\''.$RResR["Id"].'\')"').'></i></td>
                </tr>';

    if($bgcolor=='#ffffff'){$bgcolor='#cccccc';}
    elseif($bgcolor=='#cccccc'){$bgcolor='#ffffff';}

    $J++;
}

echo $cadena;