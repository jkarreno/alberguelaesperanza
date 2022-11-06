<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$_POST["id"]."' LIMIT 1"));

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    if($_POST["hacer"]=='addamopac')
    {
        mysqli_query($conn, "INSERT INTO amonestaciones (Tipo, IdPA, Amonestacion, Fecha)
                                                VALUES ('P', '".$_POST["id"]."', '".strtoupper($_POST["amonestacion"])."', '".date("Y-m-d")."')") or die(mysqli_error($conn));

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego amonestación para el paciente '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '34', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="9" style="text-align: right">| '.permisos(34, '<a href="#" onclick="add_amonestacion(\''.$ResPac["Id"].'\')" class="liga">Nueva amonestación</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="9" align="center" class="textotitable">Amonestaciones del paciente '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Fecha</th>
                    <th align="center" class="textotitable">Amonestación</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$ResAmo=mysqli_query($conn, "SELECT * FROM amonestaciones WHERE Tipo='P' AND IdPA='".$ResPac["Id"]."' ORDER BY Fecha ASC");
$J=1; $bgcolor="#ffffff";
while($RResAmo=mysqli_fetch_array($ResAmo))
{
    $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.fecha($RResAmo["Fecha"]).'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResAmo["Amonestacion"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="#" onclick="edit_amo_pac(\''.$RResAmo["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                    </td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="del_amo_pac(\''.$RResAmo["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
				</tr>';
		$J++;
		if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
		else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '33', '".json_encode($_POST)."')");
?>
<script>
function add_amonestacion(idpaciente){
	$.ajax({
				type: 'POST',
				url : 'pacientes/add_amo_pac.php',
                data: 'idpaciente=' + idpaciente
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
</script>