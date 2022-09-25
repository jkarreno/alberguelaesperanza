<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar habitacion
	if($_POST["hacer"]=='addhabitacion')
	{
		mysqli_query($conn, "INSERT INTO habitaciones (Habitacion, Tipo)
									VALUES ('".$_POST["habitacion"]."', '".$_POST["tipo"]."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la habitación '.$_POST["habitacion"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '77', '".json_encode($_POST)."')");
	}
    //editar habitacion
    if($_POST["hacer"]=='edithabitacion')
    {
        mysqli_query($conn, "UPDATE habitaciones SET Habitacion='".$_POST["habitacion"]."',
                                                        Tipo='".$_POST["tipo"]."'
                                            WHERE Id='".$_POST["idhabitacion"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la habitación '.$_POST["habitacion"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '78', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="5" style="text-align: right">| '.permisos(77, '<a href="#" onclick="add_habitacion()" class="liga">Nueva habitación</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="5" align="center" class="textotitable">Habitaciones</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Habitacion</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResHab=mysqli_query($conn, "SELECT * FROM habitaciones ORDER BY habitacion ASC");
while($RResHab=mysqli_fetch_array($ResHab))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResHab["Habitacion"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(78, '<a href="#" onclick="edit_habitacion(\''.$RResHab["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(79, '<a href="#" onclick="camas_habitacion(\''.$RResHab["Id"].'\')"><i class="fas fa-bed"></i></a>').'
                    </td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_habitacion(\''.$RResHab["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
				</tr>';
    
    $J++;
    if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
    else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}

$cadena.='  </tbody>
        </table>';



echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '76')");

?>
<script>
//funciones ajax
function add_habitacion(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/habitaciones/add_habitacion.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_habitacion(habitacion){
    $.ajax({
				type: 'POST',
				url : 'configuracion/habitaciones/edit_habitacion.php',
                data: 'habitacion=' + habitacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_habitacion(habitacion){
    $.ajax({
				type: 'POST',
				url : 'configuracion/habitaciones/del_habitacion.php',
                data: 'poblacion=' + poblacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function camas_habitacion(habitacion){
    $.ajax({
				type: 'POST',
				url : 'configuracion/habitaciones/camas_habitacion.php',
                data: 'habitacion=' + habitacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}
//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>