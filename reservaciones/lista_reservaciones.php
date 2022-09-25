<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$cadena='<table style="width:80%">'; $J=1;
$cadena.='<thead>
            <tr>
                <td colspan="6" style="text-align: right"><input type="date" name="fecha_reservacion" id="fecha_reservacion" value="'.$_POST["fecha_reservacion"].'" onchange="reservaciones(this.value)"></td>
                <td colspan="2" style="text-align: right"><a href="#" onclick="reservaciones(\''.$_POST["fecha_reservacion"].'\', \'0\', \'0\')"><i class="fas fa-calendar-alt"></i></i></a> | <a href="reservaciones/lista_pdf.php?fecha='.$_POST["fecha_reservacion"].'" target="_blank"><i class="fas fa-clipboard-list"></i></a></td>
            </tr>
        </thead>
        <tr>
            <th align="center" class="textotitable">Reservación</th>
            <th align="center" class="textotitable">Registro</th>
            <th align="center" class="textotitable">Paciente</th>
            <th align="center" class="textotitable">Fecha Reservación</th>
            <th align="center" class="textotitable">Dias</th>
            <th align="center" class="textotitable"></th>
            <th align="center" class="textotitable"></th>
            <th align="center" class="textotitable"></th>
        </tr>';

$ResRes=mysqli_query($conn, "SELECT * FROM reservaciones WHERE Fecha='".$_POST["fecha_reservacion"]."' AND Tipo='P' ORDER BY IdReservacion ASC");
$bgcolor="#ffffff"; $J=1;
while($RResRes=mysqli_fetch_array($ResRes))
{
    $Paciente=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$RResRes["IdPA"]."' LIMIT 1"));
    $Reserva=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservacion WHERE Id='".$RResRes["IdReservacion"]."' LIMIT 1"));

    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$RResRes["IdReservacion"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$RResRes["IdPA"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto">'.$Paciente["Nombre"].' '.$Paciente["Apellidos"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.fechados($Reserva["FechaReservacion"]).'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">'.$Reserva["Dias"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">';
    if($RResRes["Estatus"]==0){$cadena.='<i style="cursor: pointer;" class="far fa-circle"';}
    elseif($RResRes["Estatus"]==1){$cadena.='<i class="far fa-check-circle" style="color: #37b64b; cursor: pointer;"';}            
    elseif($RResRes["Estatus"]==2){$cadena.='<i class="far fa-times-circle" style="color: #ff0000; cursor: pointer;"';} 
    $cadena.=' onclick="document.getElementById(\'flotante_'.$J.'\').style.display = \'\';"></i>
                    <div id="flotante_'.$J.'" class="menu_flot" style="display:none;">
                        <div id="close"><i class="fas fa-times" onclick="document.getElementById(\'flotante_'.$J.'\').style.display = \'none\';"></i></div>
                        <ul>
                            <li><a href="#" onclick="confirmar(\''.$RResRes["IdReservacion"].'\', document.getElementById(\'fecha_reservacion\').value)"><i class="far fa-check-circle" style="color: #7ac70c"></i> Confirmar</a></li>
                            <li><a href="#" onclick="can_reservacion(\''.$RResRes["IdReservacion"].'\')"><i class="far fa-times-circle" style="color: #ff0000"></i> Cancelar</a></li>
                            <li><a href="#" onclick="limpiar(); abrirmodal(); edit_reservacion(\''.$RResRes["IdReservacion"].'\')"><i class="fas fa-edit" style="color: #a91b7d"></i> Editar</a></li>
                            <li><a href="#" onclick="ext_reservacion(\''.$RResRes["IdReservacion"].'\', document.getElementById(\'fecha_reservacion\').value)"><i class="fas fa-calendar-plus" style="color: #29abe2"></i> 1 día</a></li>
                            <li><a href="#" onclick="liberar_reservacion(\''.$RResRes["IdReservacion"].'\', document.getElementById(\'fecha_reservacion\').value)"><i class="fas fa-sign-out-alt" style="color: #fd7e14"></i> Liberar</a></li>
                        </ul>
                    </div>';           
    $cadena.='  </td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">';

                if($Reserva["Pagada"]==0){$cadena.='<i style="cursor: pointer; color: #ff0000;"';}
                elseif($Reserva["Pagada"]==1){$cadena.='<i style="cursor: pointer; color: #ff8500;"';}
                elseif($Reserva["Pagada"]>=2){$cadena.='<i style="cursor: pointer; color: #7ac70c;"';}
                
    $cadena.='      class="fas fa-cash-register" onclick="limpiar; abrirmodal(); pago_reservacion(\''.$RResRes["IdReservacion"].'\')"></i>
                </td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto">
                    <a href="#" onclick="limpiar(); abrirmodal(); recibo_lavanderia(\''.$RResRes["IdReservacion"].'\')"><i class="fas fa-tshirt"></i></a>
                </td>
            </tr>';

    $J++;
    if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
    else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '20', '".json_encode($_POST)."')");
?>
