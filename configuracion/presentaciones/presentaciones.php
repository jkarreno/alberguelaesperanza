<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar presentacion
	if($_POST["hacer"]=='addpresentacion')
	{
		mysqli_query($conn, "INSERT INTO presentaciones (Nombre)
									VALUES ('".strtoupper($_POST["presentacion"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la presentacion '.$_POST["presentacion"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '117', '".json_encode($_POST)."')");
	}
    //editar presentacion
    if($_POST["hacer"]=='editpresentacion')
    {
        mysqli_query($conn, "UPDATE presentaciones SET Nombre='".strtoupper($_POST["presentacion"])."'
                                            WHERE Id='".$_POST["idpresentacion"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la presentacion '.$_POST["presentacion"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '118', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(117, '<a href="#" onclick="add_presentacion()" class="liga">Nueva Presentacion</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Presentaciones</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Presentacion</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResPresentaciones=mysqli_query($conn, "SELECT * FROM presentaciones ORDER BY Nombre ASC");
while($RResUni=mysqli_fetch_array($ResPresentaciones))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResUni["Nombre"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(118, '<a href="#" onclick="edit_presentacion(\''.$RResUni["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="#" onclick="delete_presentacion(\''.$RResUni["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '116')");

?>
<script>
//funciones ajax
function add_presentacion(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/presentaciones/add_presentacion.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_presentacion(presentacion){
    $.ajax({
				type: 'POST',
				url : 'configuracion/presentaciones/edit_presentacion.php',
                data: 'presentacion=' + presentacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_poblacion(poblacion){
    $.ajax({
				type: 'POST',
				url : 'configuracion/institutos/del_instituto.php',
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