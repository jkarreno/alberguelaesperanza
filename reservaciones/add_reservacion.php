<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');


$Pc=mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2, Sexo, FechaNacimiento, Instituto1, Diagnostico1 FROM pacientes WHERE concat_ws(' ', Nombre, Apellidos) LIKE '".$_POST["nombre"]."' OR Id='".$_POST["idpaciente"]."' LIMIT 1");

$ResPac=mysqli_fetch_array($Pc);

$Pac=$ResPac["Id"];

$cadena.='<div class="c100 card" style="border:0; box-shadow: none;">
            <h2>Nueva reservación</h2>
            <form name="fadreservacion" id="fadreservacion">
                <div class="c20">
                    <label class="l_form">Num. Paciente:</label>
                    <input type="text" name="num_paciente" id="num_paciente" value="'.$ResPac["Id"].'" onchange="ad_reservacion(this.value, \'0\', document.getElementById(\'fechares\').value, document.getElementById(\'diasres\').value, \''.$_POST["habitacion"].'\')">
                </div>
                <div class="c70">
                    <label class="l_form">Paciente:</label>
                    <input list="paciente" name="paciente" type="text" onchange="ad_reservacion(\'0\', this.value, document.getElementById(\'fechares\').value, document.getElementById(\'diasres\').value, \''.$_POST["habitacion"].'\')" value="'; if(mysqli_num_rows($Pc)==0){$cadena.=$_POST["nombre"];}else{$cadena.=$ResPac["Nombre"].' '.$ResPac["Apellidos"].' '.$ResPac["Apellidos2"];}$cadena.='">
                    <datalist id="paciente">';
$ResPaci=mysqli_query($conn, "SELECT Nombre, Apellidos, Apellidos2 FROM pacientes ORDER BY Nombre");
while($RResPaci=mysqli_fetch_array($ResPaci))
{
    $cadena.='      <option value="'.$RResPaci["Nombre"].' '.$RResPaci["Apellidos"].' '.$RResPaci["Apellidos2"].'"></option>';
}
$cadena.='          </datalist>
                </div>
                <div class="c20">
                    <label class="l_form">Fecha:</label>
                    <input type="date" name="fechares" id="fechares" value="'.$_POST["fechares"].'" onchange="ad_reservacion(document.getElementById(\'num_paciente\').value, document.getElementById(\'paciente\').value, this.value, document.getElementById(\'diasres\').value)">
                </div>
                <div class="c20">
                    <label class="l_form">Dias: </label>
                    <input type="text" name="diasres" id="diasres" value="'.$_POST["diasres"].'">
                </div>
                <div class="c20">
                    <label class="l_form">Cama: </label>
                    <select name="cama" id="cama" onmousedown="if(this.options.length>5){this.size=5; this.style.height=105;}" onchange="this.size=0; this.style.height=35;" onblur="this.size=0; this.style.height=35;" required>
                        <option>Seleccione</option>
                        <option value="0">No requiere cama</option>';

    $ResCamas=mysqli_query($conn, "SELECT c.Id, h.Habitacion, c.Cama, h.Id AS hid FROM camas AS c 
                                INNER JOIN habitaciones as h ON c.Habitacion = h.Id 
                                ORDER BY h.Habitacion ASC, c.Cama ASC");

$disp=0; $hab=1;
while($RResCam=mysqli_fetch_array($ResCamas))
{
    if($_POST["idpaciente"]!=0) //si hay paciente
    {
        //checar si la cama esta disponible en fecha
        $fechares = $_POST["fechares"];
        for($j=1; $j<=$_POST["diasres"]; $j++)
        {
            $ResCamD=mysqli_query($conn, "SELECT Cama FROM reservaciones WHERE Cama='".$RResCam["Id"]."' AND Fecha='".$fechares."'"); //cama esta ocupada 
            $ResCamCan=mysqli_query($conn, "SELECT Cama FROM reservaciones WHERE Cama='".$RResCam["Id"]."' AND Fecha='".$fechares."' AND (Estatus='2' OR Liberada='1')"); //reservacion cancelada 
            if(mysqli_num_rows($ResCamD)>0 AND mysqli_num_rows($ResCamCan)==0){$disp++;}
            $fechares=date("Y-m-d",strtotime($fechares."+ 1 days"));
        }

        if($disp==0)
        {
            $cadena.='      <option value="'.$RResCam["Id"].'"';if($RResCam["hid"]==$_POST["habitacion"] AND $hab==1){$cadena.=' selected';$hab++;}$cadena.='>'.$RResCam["Habitacion"].' - '.$RResCam["Cama"].'</option>';
        }

        $disp=0;
        
    }
    else
    {
        $cadena.='          <option value="'.$RResCam["Id"].'"';if($RResCam["hid"]==$_POST["habitacion"] AND $hab==1){$cadena.=' selected';$hab++;}$cadena.='>'.$RResCam["Habitacion"].' - '.$RResCam["Cama"].'</option>';
    }
    
}
$cadena.='           </select>
                </div>
                <div class="c20"> 
                    <label class="l_form">Hospitalizado</label>
                    <ul class="tg-list">
                        <li class="tg-list-item">
                            <input class="tgl tgl-light" id="hospitalizado" name="hospitalizado" type="checkbox" value="1"/>
                            <label class="tgl-btn" for="hospitalizado"></label>
                        </li>
                    </ul>
                </div>

                <div class="c40">
                    <label class="l_form">Instituto al que asiste</label>
                    <select name="instituto" id="instituto" onmousedown="if(this.options.length>5){this.size=5; this.style.height=105;}" onchange="this.size=0; this.style.height=35;" onblur="this.size=0; this.style.height=35;">';
$ResInstitutos=mysqli_query($conn, "SELECT * FROM institutos ORDER BY Instituto ASC");
while($RResI=mysqli_fetch_array($ResInstitutos))
{
    $cadena.='          <option value="'.$RResI["Id"].'"';if($ResPac["Instituto1"]==$RResI["Id"]){$cadena.=' selected';}$cadena.='>'.$RResI["Instituto"].'</option>';
}
$cadena.='          </select> 
                </div>
                <div class="c40">
                    <label class="l_form">Diagnostico/enfermedad</label>
                    <select name="diagnostico" id="diagnostico" onmousedown="if(this.options.length>5){this.size=5; this.style.height=105;}" onchange="this.size=0; this.style.height=35;" onblur="this.size=0; this.style.height=35;">';
$ResDiagnosticos=mysqli_query($conn, "SELECT * FROM diagnosticos ORDER BY Diagnostico");
while($RResD=mysqli_fetch_array($ResDiagnosticos))
{
    $cadena.='          <option value="'.$RResD["Id"].'"';if($ResPac["Diagnostico1"]==$RResD["Id"]){$cadena.=' selected';}$cadena.='>'.$RResD["Diagnostico"].'</option>';
}
$cadena.='          </select>
                </div>

                <div class="c100">
                    <label class="l_form">Acompañantes: </label>
                </div>';
if($_POST["nombre"]!=0 OR $_POST["idpaciente"]!=0)
{
    $ResAco=mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2 FROM acompannantes WHERE IdPaciente='".$Pac."' ORDER BY Nombre ASC");
    while($RResAco=mysqli_fetch_array($ResAco))
    {
        $cadena.='  <div class="c10">
                        <ul class="tg-list">
                        <li class="tg-list-item">
                            <input class="tgl tgl-light" id="aco_'.$RResAco["Id"].'" name="aco_'.$RResAco["Id"].'" type="checkbox" value="1"/>
                            <label class="tgl-btn" for="aco_'.$RResAco["Id"].'"></label>
                        </li>
                        </ul>
                    </div>
                    <div class="c60"> 
                        '.$RResAco["Nombre"].' '.$RResAco["Apellidos"].' '.$RResAco["Apellidos2"].'
                    </div> 
                    <div class="c30"> 
                        <select name="cama_aco_'.$RResAco["Id"].'" id="cama_aco_'.$RResAco["Id"].'" onmousedown="if(this.options.length>5){this.size=5; this.style.height=105;}" onchange="this.size=0; this.style.height=35;" onblur="this.size=0; this.style.height=35;">
                            <option value="0">Seleccione</option>';

            $ResCamas=mysqli_query($conn, "SELECT c.Id, h.Habitacion, c.Cama, h.Id AS hid FROM camas AS c 
                                        INNER JOIN habitaciones as h ON c.Habitacion = h.Id 
                                        ORDER BY h.Habitacion ASC, c.Cama ASC");
        
        $disp=0; $hab=1;
        while($RResCam=mysqli_fetch_array($ResCamas))
        {
            //checar si la cama esta disponible en fecha
            $fechares = $_POST["fechares"];
            for($j=1; $j<=$_POST["diasres"]; $j++)
            {
                $ResCamD=mysqli_query($conn, "SELECT Cama FROM reservaciones WHERE Cama='".$RResCam["Id"]."' AND Fecha='".$fechares."'");
                $ResCamCan=mysqli_query($conn, "SELECT Cama FROM reservaciones WHERE Cama='".$RResCam["Id"]."' AND Fecha='".$fechares."' AND (Estatus='2' OR Liberada='1')"); //reservacion cancelada 
                if(mysqli_num_rows($ResCamD)>0 AND mysqli_num_rows($ResCamCan)==0){$disp++;}
                $fechares=date("Y-m-d",strtotime($fechares."+ 1 days"));
            }
        
            if($disp==0)
            {
                $cadena.='      <option value="'.$RResCam["Id"].'"';if($RResCam["hid"]==$_POST["habitacion"] AND $hab==1){$cadena.=' selected'; $hab++;}$cadena.='>'.$RResCam["Habitacion"].' - '.$RResCam["Cama"].'</option>';
            }
        
            $disp=0;
        }
        $cadena.='      </select> 
                    </div>';
    }
    
}


    $cadena.='  <div class="c100"> 
                    <input type="hidden" name="hacer" id="hacer" value="adreservacion">
                    <input type="hidden" name="habitacion" id="habitacion" value="'.$_POST["habitacion"].'">
                    <input type="submit" name="botadreserv" id="botadreserv" value="Reservar>>" onclick="cerrarmodal()">
                </div>';

$cadena.='  </form>
        </div>';


if($ResPac["Id"]!=NULL)
{
    //muestra reservaciones anteriores
    $TotResPac=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE IdPaciente='".$ResPac["Id"]."'"));
    $TotResPacCon=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE IdPaciente='".$ResPac["Id"]."' AND Estatus='1'"));
    $TotResPacCan=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reservacion WHERE IdPaciente='".$ResPac["Id"]."' AND Estatus='2'"));

    $cadena.='<div class="c100">
                <table style="width:80%">
                <thead>
                    <tr>
                        <td colspan="3" style="text-align: left">Total Reservaciones: '.$TotResPac.' | Confirmadas: '.$TotResPacCon.' | Canceladas: '.$TotResPacCan.'</td>
                    </tr>
                    <tr>
                        <th colspan="3" align="center" class="textotitable">Reservaciones</td>
                    </tr>
                    <tr>
                        <th align="center" class="textotitable">&nbsp;</th>
                        <th align="center" class="textotitable">Fecha</th>
                        <th align="center" class="textotitable">Estatus</th>
                    </tr>
                </thead>
                <tbody>';
    $ResResP=mysqli_query($conn, "SELECT * FROM reservacion WHERE IdPaciente='".$ResPac["Id"]."' ORDER BY Fecha DESC");
    $J=1; $bgcolor='#ffffff';
    while($ResRP=mysqli_fetch_array($ResResP))
    {
        $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.fecha($ResRP["Fecha"]).'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">';
        if($ResRP["Estatus"]==1){$cadena.=' Confirmada';}
        if($ResRP["Estatus"]==2){$cadena.=' Cancelada';}
        $cadena.='      </td>
                    </tr>';

        $J++;
        if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
        else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
    }
    $cadena.='  </table>
            </div>';

    //muestra reconocimientos
    $cadena.='<div class="c100" style="margin-top: 20px">
                <table style="width:80%">
                <thead>
                    <tr>
                        <td colspan="3" style="text-align: left"></td>
                    </tr>
                    <tr>
                        <th colspan="4" align="center" class="textotitable">Reconocimientos</td>
                    </tr>
                    <tr>
                        <th align="center" class="textotitable">&nbsp;</th>
                        <th align="center" class="textotitable">Fecha</th>
                        <th align="center" class="textotitable">Paciente/Acompañante</th>
                        <th align="center" class="textotitable">Reconocimento</th>
                    </tr>
                </thead>
                <tbody>';
    $ResRecPac=mysqli_query($conn, "SELECT * FROM reconocimientos WHERE Tipo='P' AND IdPA='".$ResPac["Id"]."' ORDER BY Fecha DESC");
    $J=1; $bgcolor='#ffffff';
    while($RResRP=mysqli_fetch_array($ResRecPac))
    {
        $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.fecha($RResRP["Fecha"]).'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResRP["Amonestacion"].'</td>
                    </tr>';

        $J++;
        if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
        else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
    }
    $ResPacAco=mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM acompannantes WHERE IdPaciente='".$ResPac["Id"]."' ORDER BY Id DESC");
    $bgcolor='#ffffff';
    while($RResPA=mysqli_fetch_array($ResPacAco))
    {
        $ResRecAco=mysqli_query($conn, "SELECT * FROM reconocimientos WHERE Tipo='A' AND IdPA='".$RResPA["Id"]."' ORDER BY Fecha DESC");
        while($RResRA=mysqli_fetch_array($ResRecAco))
        {
            $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.fecha($RResRA["Fecha"]).'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResPA["Nombre"].' '.$RResPA["Apellidos"].'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResRA["Reconocimiento"].'</td>
                    </tr>';

            $J++;
            if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
            else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
        }
    }
    $cadena.='</div>';

    //muestra amonestaciones
    $cadena.='<div class="c100" style="margin-top: 20px">
                <table style="width:80%">
                <thead>
                    <tr>
                        <td colspan="3" style="text-align: left"></td>
                    </tr>
                    <tr>
                        <th colspan="4" align="center" class="textotitable">Amonestaciones</td>
                    </tr>
                    <tr>
                        <th align="center" class="textotitable">&nbsp;</th>
                        <th align="center" class="textotitable">Fecha</th>
                        <th align="center" class="textotitable">Paciente/Acompañante</th>
                        <th align="center" class="textotitable">Amonestación</th>
                    </tr>
                </thead>
                <tbody>';
    $ResAmoPac=mysqli_query($conn, "SELECT * FROM amonestaciones WHERE Tipo='P' AND IdPA='".$ResPac["Id"]."' ORDER BY Fecha DESC");
    $J=1; $bgcolor='#ffffff';
    while($RResAP=mysqli_fetch_array($ResAmoPac))
    {
        $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.fecha($RResAP["Fecha"]).'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResAP["Amonestacion"].'</td>
                    </tr>';

        $J++;
        if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
        else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
    }
    $ResPacAco=mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM acompannantes WHERE IdPaciente='".$ResPac["Id"]."' ORDER BY Id DESC");
    $bgcolor='#ffffff';
    while($RResPA=mysqli_fetch_array($ResPacAco))
    {
        $ResAmoAco=mysqli_query($conn, "SELECT * FROM amonestaciones WHERE Tipo='A' AND IdPA='".$RResPA["Id"]."' ORDER BY Fecha DESC");
        while($RResAA=mysqli_fetch_array($ResAmoAco))
        {
            $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.fecha($RResAP["Fecha"]).'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResPA["Nombre"].' '.$RResPA["Apellidos"].'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResAA["Amonestacion"].'</td>
                    </tr>';

            $J++;
            if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
            else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
        }
    }
    $cadena.='</div>';

    //muestra observaciones de lavandería
    $cadena.='<div class="c100" style="margin-top: 20px">
                <table style="width:80%">
                <thead>
                    <tr>
                        <td colspan="3" style="text-align: left"></td>
                    </tr>
                    <tr>
                        <th colspan="4" align="center" class="textotitable">Observaciones Lavandería</td>
                    </tr>
                    <tr>
                        <th align="center" class="textotitable">&nbsp;</th>
                        <th align="center" class="textotitable">Reservación</th>
                        <th align="center" class="textotitable">Recibio/Entrego</th>
                        <th align="center" class="textotitable">Observaciones</th>
                    </tr>
                </thead>
                <tbody>';
    $ResReservaciones=mysqli_query($conn, "SELECT Id FROM reservacion WHERE IdPaciente='".$ResPac["Id"]."' ORDER BY Id ASC");
    $bgcolor='#ffffff'; $J=1;
    while($RResR=mysqli_fetch_array($ResReservaciones))
    {
        $ResObservaciones=mysqli_query($conn, "SELECT *  FROM lavanderia_observaciones WHERE IdReservacion='".$RResR["Id"]."' AND Observaciones!=''");
        while($RResObs=mysqli_fetch_array($ResObservaciones))
        {
            if($RResObs["PA"]=='P')
            {
                $ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos, Apellidos2 FROM pacientes WHERE Id='".$RResObs["IdPA"]."' LIMIT 1"));
                $Nombre=$ResP["Nombre"].' '.$ResP["Apellidos"].' '.$ResP{"Apellidos2"};
            }
            elseif($RResObs["PA"]=='A')
            {
                $ResA=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos, Apellidos2 FROM pacientes WHERE Id='".$RResObs["IdPA"]."' LIMIT 1"));
                $Nombre=$ResA["Nombre"].' '.$ResA["Apellidos"].' '.$ResA["Apellidos2"];
            }

            $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResR["Id"].'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResObs["PA"].$RResObs["IdPA"].' '.$Nombre.'</td>
                        <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResObs["Observaciones"].'</td>
                    </tr>';

            if($bgcolor=='#ffffff'){$bgcolor='#cccccc';}
            elseif($bgcolor=='#cccccc'){$bgcolor='#ffffff';}
            $J++;
        } 
    }

    $cadena.='  </tbody>
                </table>
            </di>';
}

echo $cadena;

?>
<script>
$("#fadreservacion").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadreservacion"));

	$.ajax({
		url: "reservaciones/reservaciones.php",
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

function edit_paciente(paciente){
	$.ajax({
				type: 'POST',
				url : 'pacientes/edit_paciente.php',
                data: 'paciente=' + paciente
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
</script>