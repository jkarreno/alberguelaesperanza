<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$mensaje='';

if(isset($_POST["hacer"]))
{
    //cancelar recibo
	if($_POST["hacer"]=='cancelar')
	{
        if($_POST["cancelar"]=='no')
        {
            $mensaje='<div class="mesaje">
                        <i class="fas fa-exclamation-triangle"></i> Se cancelara el recibo '.$_POST["idrecibo"].'<br />
                        <a href="#" onclick="cancelar_recibo_medico(document.getElementById(\'fechaini\').value, document.getElementById(\'fechafin\').value, \''.$_POST["idrecibo"].'\', \'si\')">Si</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="caja(document.getElementById(\'fechaini\').value, document.getElementById(\'fechafin\').value)">No</a>
                    </div>';
        }
        elseif($_POST["cancelar"]=='si')
        {
            //cancela recibo
            mysqli_query($conn, "UPDATE pagogastosmedicos SET Estatus='0' WHERE Id='".$_POST["idrecibo"]."'");
            

            $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se cancelo el recibo '.$_POST["idrecibo"].'</div>';

            //bitacora
            mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '128', '".json_encode($_POST)."')");
        }
    }
}

$ResTotal=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CantidadEntregada) AS TotMonto FROM pagogastosmedicos WHERE Fecha>='".$_POST["fechaini"]."' AND Fecha<='".$_POST["fechafin"]."'"));

$cadena=$mensaje.'<form name="fbusrecmed" id="fbusrecmed" style="width:100%">
        <table>
            <thead>
                <tr>
                    <td>'.permisos(112, '<a href="javascript:void(0);" onclick="caja(\''.date ("Y-m-d").'\', \''.date ("Y-m-d").'\')"><i class="fas fa-cash-register" aria-hidden="true"></i></a>').'</td>
                    <td>Paciente: <input type="text" name="numpaciente" id="numpaciente" placeholder="num- paciente">
                    <td>De: <input type="date" name="fechaini" id="fechaini" value="'.$_POST["fechaini"].'"> </td>
                    <td>Hasta: <input type="date" name="fechafin" id="fechafin" value="'.$_POST["fechafin"].'"> </td>
                    <td align="left"><input type="submit" name="botbuscaja" id="botbuscaja" value="Consultar>"></td>
                    <td align="right" class="texto" valign="middle"><strong>$ '.number_format($ResTotal["TotMonto"], 2).'</strong></td>
                    <td align="right">'.permisos(126, '<a href="caja/reporte_pagos_cuotas_medicos.php?fechaini='.$_POST["fechaini"].'&fechafin='.$_POST["fechafin"].'" target="_blank"><i class="fas fa-print"></i></a>').'</td>
                    <td align="right">'.permisos(127, '<a href="caja/reporte_pagos_cuotas_medicos_excel.php?fechaini='.$_POST["fechaini"].'&fechafin='.$_POST["fechafin"].'" target="_blank"><i class="far fa-file-excel"></i></a>').'</td>
                </tr>
                <tr>
                    <th colspan="8" align="center" class="textotitable">Recibos Apoyos Medicos</td>
                </tr>
                <tr>
                    <th width="10" align="center" class="textotitable">#</th>
                    <th width="50" align="center" class="textotitable">N. Recibo</th>
                    <th width="50" align="center" class="textotitable">Fecha</th>
                    <th width="50" align="center" class="textotitable">N. Paciente</th>
                    <th align="center" class="textotitable">Paciente</th>
                    <th width="150" align="center" class="textotitable">Monto</th>
                    <th width="50" align="center" class="textotitable"></th>
                    <th width="50" align="center" class="textotitable"></th>
                </tr>
            </thead>
            <tbody>';

$ResRecibos=mysqli_query($conn, "SELECT * FROM pagogastosmedicos WHERE Fecha>='".$_POST["fechaini"]."' AND Fecha<='".$_POST["fechafin"]."' ORDER BY Id DESC");

$bgcolor="#ffffff"; $J=1;
while($RResRec=mysqli_fetch_array($ResRecibos))
{
    $ResPaciente=mysqli_fetch_array(mysqli_query($conn, "SELECT concat_ws(' ', Nombre, Apellidos) AS NombrePaciente 
                                                        FROM pacientes WHERE Id='".$RResRec["IdPaciente"]."' LIMIT 1"));

    if($RResRec["Estatus"]==0){$bgcolor='#ff0000';}

    $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td width="10" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle"><strong>'.$RResRec["Id"].'</strong></td>
					<td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.fecha($RResRec["Fecha"]).'</td>
					<td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResRec["IdPaciente"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.utf8_encode($ResPaciente["NombrePaciente"]).'</td>
					<td width="150" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle"><strong>$ '.number_format($RResRec["CantidadEntregada"],2).'</strong></td>
					<td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="caja/recibo_ayuda_medica.php?idrecibo='.$RResRec["Id"].'" target="_blank"><i class="fas fa-print"></i></a>
                    </td>
					<td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(128, '<a href="#" onclick="cancelar_recibo_medico(document.getElementById(\'fechaini\').value, document.getElementById(\'fechafin\').value, \''.$RResRec["Id"].'\', \'no\')"><i class="far fa-times-circle"></i></a>').'
                    </td>
                </tr>';
		$J++;
		if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
		else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
        else if($bgcolor=='#ff0000'){$bgcolor='#ffffff';}
}

$cadena.='  </tbody>
        </table>
        </form>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '112', '".json_encode($_POST)."')");

?>
<script>
$("#fbusrecmed").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fbusrecmed"));

	$.ajax({
		url: "caja/caja_medicos.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#contenido").html(echo);
	});
});

function cancelar_recibo_medico(fechaini, fechafin, idrecibo, cancelar){
	$.ajax({
				type: 'POST',
				url : 'caja/caja_medicos.php',
				data: 'fechaini=' + fechaini + '&fechafin=' + fechafin +'&idrecibo=' + idrecibo + '&hacer=cancelar&cancelar=' + cancelar
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
</script>