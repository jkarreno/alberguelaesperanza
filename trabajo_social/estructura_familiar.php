<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

if(isset($_POST["hacer"]))
{
    //borra familiar
    mysqli_query($conn, "DELETE FROM es_estructurafamiliar WHERE Id='".$_POST["familiar"]."'");
}
else
{
    //insertar datos
    mysqli_query($conn, "INSERT INTO es_estructurafamiliar (IdPaciente, Nombre, Parentesco, Edad, Ocupacion, Usuario, Fecha)
                                                    VALUES ('".$_POST["idpaciente"]."', '".strtoupper($_POST["nombre"])."', '".strtoupper($_POST["parentesco"])."', 
                                                            '".$_POST["edad"]."', '".strtoupper($_POST["ocupacion"])."', '".$_SESSION["Id"]."', 
                                                            '".date("Y-m-d")."')") or die(mysqli_error($conn));
}


$ResES=mysqli_query($conn, "SELECT * FROM es_estructurafamiliar WHERE IdPaciente='".$_POST["idpaciente"]."' ORDER BY Id ASC");

$cadena.='<table>
            <tbody>';

$J=1; $bgcolor="#ffffff";
while($RResEs=mysqli_fetch_array($ResES))
{
    $cadena.='  <tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEs["Nombre"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEs["Parentesco"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEs["Edad"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEs["Ocupacion"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="javascript:void(0)" onclick="del_familiar(\''.$RResEs["Id"].'\')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>';

    $J++;
    if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
    else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}
                
$cadena.='  </tbody>
        </table>';

echo $cadena;

?>