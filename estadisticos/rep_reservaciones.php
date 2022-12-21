<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

switch ($_POST["reser"])
{
    case '0': $reservaciones='por confirmar'; break;
    case '1': $reservaciones='confirmadas'; break;
    case '2': $reservaciones='canceladas'; break;
}

switch ($_POST["mes"])
{
    case '01': $mes='enero'; break;
    case '02': $mes='febrero'; break;
    case '03': $mes='marzo'; break;
    case '04': $mes='abril'; break;
    case '05': $mes='mayo'; break;
    case '06': $mes='junio'; break;
    case '07': $mes='julio'; break;
    case '08': $mes='agosto'; break;
    case '09': $mes='septiembre'; break;
    case '10': $mes='octubre'; break;
    case '11': $mes='noviembre'; break;
    case '12': $mes='diciembre'; break;
}

$cadena='<h2>Reservaciones '.$reservaciones.' '.$mes.' '.$_POST["anno"].'</h2>
        <table>
            <thead>
                <tr>
                    <th align="center" class="textotitable">#</th>
                    <th align="center" class="textotitable">Reservación</th>
                    <th align="center" class="textotitable">Fecha</th>
                    <th align="center" class="textotitable">Paciente</th>
                </tr>
            </thead>
            <tbody>';

$ResReservaciones=mysqli_query($conn, "SELECT * FROM reservacion WHERE Fecha LIKE '".$_POST["anno"]."-".$_POST["mes"]."-%' AND Estatus='".$_POST["reser"]."' ORDER BY Id ASC");
$bgcolor='#ffffff'; $J=1;
while($RResRes=mysqli_fetch_array($ResReservaciones))
{
    $ResPaciente=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos, Apellidos2 FROM pacientes WHERE Id='".$RResRes["IdPaciente"]."' LIMIT 1"));

    $cadena.='  <tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$J.'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$RResRes["Id"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$RResRes["Fecha"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">'.$ResPaciente["Nombre"].' '.$ResPaciente["Apellidos"].' '.$ResPaciente["Apellidos2"].'</td>
                </tr>';

    if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
    else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}

    $J++;
}

echo $cadena;

?>