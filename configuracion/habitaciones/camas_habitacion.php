<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$ResHab=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM habitaciones WHERE Id='".$_POST["habitacion"]."' LIMIT 1"));

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar cama
	if($_POST["hacer"]=='addcama')
	{
		mysqli_query($conn, "INSERT INTO camas (Habitacion, Cama)
									VALUES ('".$_POST["habitacion"]."', '".$_POST["cama"]."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la cama '.$_POST["cama"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '80', '".json_encode($_POST)."')");
	}
    //editar cama
    if($_POST["hacer"]=='editcama')
    {
        mysqli_query($conn, "UPDATE camas SET Cama='".$_POST["cama"]."'
                                            WHERE Id='".$_POST["idcama"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la cama '.$_POST["cama"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '81', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(80, '<a href="#" onclick="add_cama(\''.$ResHab["Id"].'\')" class="liga">Nueva Cama</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Camas de la habitación '.$ResHab["Habitacion"].'</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Cama</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResCam=mysqli_query($conn, "SELECT * FROM camas WHERE Habitacion='".$ResHab["Id"]."' ORDER BY cama ASC");
while($RResCam=mysqli_fetch_array($ResCam))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResCam["Cama"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(81, '<a href="#" onclick="edit_cama(\''.$RResCam["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_cama(\''.$RResCam["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '79')");

?>
<script>
//funciones ajax
function add_cama(habitacion){
	$.ajax({
				type: 'POST',
				url : 'configuracion/habitaciones/add_cama.php',
                data: 'habitacion=' + habitacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_cama(cama){
    $.ajax({
				type: 'POST',
				url : 'configuracion/habitaciones/edit_cama.php',
                data: 'cama=' + cama
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_cama(cama){
    $.ajax({
				type: 'POST',
				url : 'configuracion/camaes/del_cama.php',
                data: 'cama=' + cama
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>