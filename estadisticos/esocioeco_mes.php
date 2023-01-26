<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$anno=$_POST["anno"];
$mes=$_POST["mes"];

$cadena='<h2>Reservaciones '; if($mes!='%'){$cadena.=mes($mes).' - ';}$cadena.=$anno.'</h2>
        <div class="c100 card">
            <div class="c100" style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; align-content: center;">
                <table>
                    <thead>
                        <tr>
                            <th align="center" class="textotitable">#</th>
                            <th align="center" class="textotitable">Fecha</th>
                            <th align="center" class="textotitable">Registro</th>
                            <th align="center" class="textotitable">Paciente</th>
                            <th align="center" class="textotitable"></th>
                        </tr>
                    </thead>
                    <tbody>';
$ResEstudio=mysqli_query($conn, "SELECT es.Id, es.IdPaciente, es.Fecha, concat_ws(' ', p.Nombre, p.Apellidos, p.Apellidos2) AS Nombre 
                                    FROM es_salud AS es 
                                    INNER JOIN pacientes AS p ON p.Id=es.IdPaciente 
                                    WHERE es.Fecha LIKE '".$_POST["anno"]."-".$_POST["mes"]."-%' 
                                    ORDER BY es.Fecha ASC");
$bgcolor='#ffffff'; $J=1;
while($RResEs=mysqli_fetch_array($ResEstudio))
{
    $cadena.='          <tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                            <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                            <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.fecha($RResEs["Fecha"]).'</td>
                            <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResEs["IdPaciente"].'</td>
                            <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEs["Nombre"].'</td>
                            <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle"><a href="trabajo_social/estudio.php?paciente='.$RResEs["IdPaciente"].'" target="_blank"><i class="fas fa-print"></i></a></td>
                        </tr>';
    if($bgcolor=='#ffffff'){$bgcolor='#ccc';}
    elseif($bgcolor=='#ccc'){$bgcolor='#ffffff';}

    $J++;
}
$cadena.='          </tbody>
                </table>
            </div>
        </div>';

echo $cadena;