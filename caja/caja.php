<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$mensaje='';

//1 - pagado | 0 - cancelado

if(isset($_POST["hacer"]))
{
    //cancelar recibo
	if($_POST["hacer"]=='cancelar')
	{
        if($_POST["cancelar"]=='no')
        {
            $mensaje='<div class="mesaje">
                        <i class="fas fa-exclamation-triangle"></i> Se cancelara el recibo '.$_POST["idrecibo"].'<br />
                        <a href="#" onclick="cancelar_recibo(document.getElementById(\'fechaini\').value, document.getElementById(\'fechafin\').value, \''.$_POST["idrecibo"].'\', \'si\')">Si</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="caja(document.getElementById(\'fechaini\').value, document.getElementById(\'fechafin\').value)">No</a>
                    </div>';
        }
        elseif($_POST["cancelar"]=='si')
        {
            //cancela recibo
            mysqli_query($conn, "UPDATE pagoreservacion SET Estatus='0' WHERE Id='".$_POST["idrecibo"]."'");
            //actualiza reservacion
            $ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT IdReservacion FROM pagoreservacion WHERE Id='".$_POST["idrecibo"]."' LIMIT 1"));
            ///Selecciona paos de la reservacion
            $ResSTR=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Monto) AS Total FROM `pagoreservacion` WHERE IdReservacion='".$ResRes["IdReservacion"]."' AND Estatus='1'"));

            if($ResSTR["Total"]==0 OR $ResSTR["Total"]==NULL)
            {
                mysqli_query($conn, "UPDATE reservacion SET Pagada='0' WHERE Id='".$ResRes["IdReservacion"]."'");
            }
            else
            {
                mysqli_query($conn, "UPDATE reservacion SET Pagada='2' WHERE Id='".$ResRes["IdReservacion"]."'");
            }

            

            $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se cancelo el recibo '.$_POST["idrecibo"].'</div>';
        }
    }
}

$ResTotal=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Monto) AS TotMonto FROM pagoreservacion WHERE Fecha>='".$_POST["fechaini"]."' AND Fecha<='".$_POST["fechafin"]."' AND Estatus='1'"));

$cadena=$mensaje.'<form name="fbusrecibos" id="fbusrecibos" style="width:100%">
        <table>
            <thead>
                <tr>
                    <td>'.permisos(112, '<a href="javascript:void(0);" onclick="caja_medicos(\''.date ("Y-m-d").'\', \''.date ("Y-m-d").'\')"><i class="fas fa-file-medical"></i></a>').'</td>
                    <td colspan="2">Paciente: <input type="text" name="numpaciente" id="numpaciente" placeholder="num- paciente">
                    <td>De: <input type="date" name="fechaini" id="fechaini" value="'.$_POST["fechaini"].'"> </td>
                    <td>Hasta: <input type="date" name="fechafin" id="fechafin" value="'.$_POST["fechafin"].'"> </td>
                    <td align="left"><input type="submit" name="botbuscaja" id="botbuscaja" value="Consultar>"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="right" class="texto" valign="middle"><strong>$ '.number_format($ResTotal["TotMonto"], 2).'</strong></td>
                    <td align="right">'.permisos(111, '<a href="caja/reporte_pagos_cuotas.php?fechaini='.$_POST["fechaini"].'&fechafin='.$_POST["fechafin"].'" target="_blank"><i class="fas fa-print"></i></a>').'</td>
                    <td align="right">'.permisos(110, '<a href="caja/reporte_pagos_cuotas_excel.php?fechaini='.$_POST["fechaini"].'&fechafin='.$_POST["fechafin"].'" target="_blank"><i class="far fa-file-excel"></i></a>').'</td>
                </tr>
                <tr>
                    <th colspan="12" align="center" class="textotitable">Recibos Reservaciones</td>
                </tr>
                <tr>
                    <th width="10" align="center" class="textotitable">#</th>
                    <th width="10" align="center" class="textotitable">Cobrado por</th>
                    <th width="50" align="center" class="textotitable">N. Recibo</th>
                    <th width="50" align="center" class="textotitable">Fecha</th>
                    <th width="50" align="center" class="textotitable">N. Paciente</th>
                    <th align="center" class="textotitable">Paciente</th>
                    <th width="60" align="center" class="textotitable">Reservación</th>
                    <th width="60" align="center" class="textotitable">Días Paciente</th>
                    <th width="60" align="center" class="textotitable">Días Acompañante</th>
                    <th width="150" align="center" class="textotitable">Monto</th>
                    <th width="50" align="center" class="textotitable"></th>
                    <th width="50" align="center" class="textotitable"></th>
                </tr>
            </thead>
            <tbody>';


$ResRecibos=mysqli_query($conn, "SELECT * FROM pagoreservacion WHERE Fecha>='".$_POST["fechaini"]."' AND Fecha<='".$_POST["fechafin"]."' ORDER BY Id DESC");



$bgcolor="#ffffff"; $J=1;
while($RResRec=mysqli_fetch_array($ResRecibos))
{
    $ResPaciente=mysqli_fetch_array(mysqli_query($conn, "SELECT p.Id AS IdP, concat_ws(' ', p.Nombre, p.Apellidos) AS NombrePaciente, r.Dias AS Dias FROM reservacion AS r 
                                            INNER JOIN pacientes AS p ON r.IdPaciente=p.Id 
                                            WHERE r.Id='".$RResRec["IdReservacion"]."'"));
   if($RResRec["Estatus"]==0){$bgcolor='#ff0000'; $RResRec["Monto"]=0;}

   if($RResRec["Usuario"]==0){$cobradoby='---';}
    else
    {
        $ResUsuario=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM usuarios WHERE Id='".$RResRec["Usuario"]."' LIMIT 1"));
        $cobradoby=$ResUsuario["Nombre"];
    }

    //dias paciente
    $ResDP=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS Dias FROM reservaciones WHERE IdReservacion='".$RResRec["IdReservacion"]."' AND Tipo='P' AND Cama>0"));

    //dias acompañante
    $ResDA=mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(Id) AS Dias FROM reservaciones WHERE IdReservacion='".$RResRec["IdReservacion"]."' AND Tipo='A' AND Cama>0"));


    $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td width="10" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td width="10" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$cobradoby.'</td>
					<td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle"><strong>'.$RResRec["Id"].'</strong></td>
					<td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.fecha($RResRec["Fecha"]).'</td>
					<td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResPaciente["IdP"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.utf8_encode($ResPaciente["NombrePaciente"]).'</td>
					<td width="60" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResRec["IdReservacion"].'</td>
					<td width="60" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResDP["Dias"].'</td>
					<td width="60" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResDA["Dias"].'</td>
					<td width="150" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="right" class="texto" valign="middle"><strong>$ '.number_format($RResRec["Monto"],2).'</strong></td>
					<td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">';
    if($RResRec["Estatus"]==1)
    {                
        $cadena.='      <a href="caja/recibo_reservacion.php?idrecibo='.$RResRec["Id"].'" target="_blank"><i class="fas fa-print"></i></a>';
    }
    $cadena.='      </td>
					<td width="50" onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">';
    if($RResRec["Estatus"]==1)
    {
        $cadena.='      <a href="#" onclick="cancelar_recibo(document.getElementById(\'fechaini\').value, document.getElementById(\'fechafin\').value, \''.$RResRec["Id"].'\', \'no\')"><i class="far fa-times-circle"></i></a>';
    }
    $cadena.='      </td>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '109', '".json_encode($_POST)."')");

?>
<script>
$("#fbusrecibos").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fbusrecibos"));

	$.ajax({
		url: "caja/caja.php",
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

function cancelar_recibo(fechaini, fechafin, idrecibo, cancelar){
	$.ajax({
				type: 'POST',
				url : 'caja/caja.php',
				data: 'fechaini=' + fechaini + '&fechafin=' + fechafin +'&idrecibo=' + idrecibo + '&hacer=cancelar&cancelar=' + cancelar
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function caja_medicos(fechaini, fechafin, paciente){
	$.ajax({
				type: 'POST',
				url : 'caja/caja_medicos.php',
                data: 'fechaini=' + fechaini + '&fechafin=' + fechafin
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}


//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>