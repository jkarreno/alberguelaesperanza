<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$ResPL=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM lavanderia WHERE Id='".$_POST["prenda"]."' LIMIT 1")); //Prenda Lavanderia

$cadena='<h2>Movimientos de lavandería para '.utf8_encode($ResPL["Prenda"]).'</h2>
        <div class="c100 card">
            <!--<form name="frestiempo" id="frestiempo">
                <div class="c25">
                    De: <input type="date" name="fechai" id="fechai" style="width: calc(100% - 50px);"> 
                </div>
                <div class="c25">
                    A: <input type="date" name="fechaf" id="fechaf" style="width: calc(100% - 50px);"> 
                </div>
                <div class="c25">
                    <input type="submit" name="bot_periodo" id="bot_periodo" value="Ver">
                </div>
                <div class="c25">
                </div>
            </form>-->
        </div>
        <div class="c100 card">
            <table>
            <thead>
                <tr>
                    <th align="center" class="textotitable">#</th>
                    <th align="center" class="textotitable">Reservación</th>
                    <th align="center" class="textotitable">Paciente</th>
                    <th align="center" class="textotitable">Fecha</th>
                    <th align="center" class="textotitable">E/S</th>
                    <th align="center" class="textotitable">Cantidad</th>
                    <th align="center" class="textotitable">Balance</th>
                </tr>
            </thead>
            <tbody>';
$ResInvP=mysqli_query($conn, "SELECT * FROM lavanderia_inventario WHERE IdPrenda='".$_POST["prenda"]."' ORDER BY Id DESC");

$l=1; $bgcolor='#fff'; $balance=0;
while($RResIP=mysqli_fetch_array($ResInvP))
{

    if($RResIP["IdReservacion"]==0 AND $RResIP["IdPA"]==0 AND $RResIP["PA"]=='I')
    {
        $RResIP["IdReservacion"]='---';
        $paciente='CAPTURA DE INVENTARIO';
    }
    else
    {
        $ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT p.Nombre, p.Apellidos FROM reservacion AS r
                                                        INNER JOIN pacientes AS p on r.IdPaciente=p.Id
                                                        WHERE r.Id='".$RResIP["IdReservacion"]."'"));

        $paciente=$ResRes["Nombre"].' '.$ResRes["Apellido"];
    }

    switch($RResIP["ES"])
    {
        case 'I': $mov='Inventario'; $s='<i class="fas fa-plus" style="font-size: 16px;"></i>'; $estilo='#003366'; break;
        case 'E': $mov='Entrada'; $s='<i class="fas fa-plus" style="font-size: 16px;"></i>'; $estilo='#26b719'; break;
        case 'S': $mov='Salida'; $s='<i class="fas fa-minus" style="font-size: 16px;"></i>'; $estilo='#ff0000'; break;
        case 'C': $mov='Cambio'; $s='<i class="fas fa-exchange-alt" style="font-size: 16px;"></i>'; $estilo='#003366'; break;
    }

    $cadena.='<tr style="background: '.$bgcolor.'" id="i_row_'.$l.'">
                <td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$l.'</td>
                <td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResIP["IdReservacion"].'</td>
                <td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$paciente.'</td>
                <td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.fecha($RResIP["Fecha"]).'</td>
                <td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$mov.'</td>
                <td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle" style="color: '.$estilo.'">'.$s.number_format($RResIP["Cantidad"]).'</td>
                <td onmouseover="i_row_'.$l.'.style.background=\'#badad8\'" onmouseout="i_row_'.$l.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle">'.number_format($RResIP["Balance"]).'</td>
            </tr>';

    if($bgcolor=='#fff'){$bgcolor='#ccc';}
    elseif($bgcolor=='#ccc'){$bgcolor='#fff';}

    $l++;
}
$cadena.=' </div>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '107', '".json_encode($_POST)."')");

?>