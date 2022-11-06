<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar ocupacion
	if($_POST["hacer"]=='addocupacion')
	{
		mysqli_query($conn, "INSERT INTO ocupaciones (ocupacion)
									VALUES ('".strtoupper($_POST["ocupacion"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la ocupacion '.$_POST["ocupacion"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '62', '".json_encode($_POST)."')");
	}
    //editar ocupacion
    if($_POST["hacer"]=='editocupacion')
    {
        mysqli_query($conn, "UPDATE ocupaciones SET ocupacion='".strtoupper($_POST["ocupacion"])."'
                                            WHERE Id='".$_POST["idocupacion"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la ocupacion '.$_POST["ocupacion"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '63', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(62, '<a href="#" onclick="add_ocupacion()" class="liga">Nueva ocupacion</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Ocupaciones</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Ocupacion</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResOcu=mysqli_query($conn, "SELECT * FROM ocupaciones ORDER BY Ocupacion ASC");
while($RResOcu=mysqli_fetch_array($ResOcu))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResOcu["Ocupacion"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(63, '<a href="#" onclick="edit_ocupacion(\''.$RResOcu["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_ocupacion(\''.$RResOcu["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '61')");

?>
<script>
//funciones ajax
function add_ocupacion(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/ocupaciones/add_ocupacion.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_ocupacion(ocupacion){
    $.ajax({
				type: 'POST',
				url : 'configuracion/ocupaciones/edit_ocupacion.php',
                data: 'ocupacion=' + ocupacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_ocupacion(poblacion){
    $.ajax({
				type: 'POST',
				url : 'configuracion/ocupaciones/del_ocupacion.php',
                data: 'poblacion=' + poblacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>