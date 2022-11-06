<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar edocivil
	if($_POST["hacer"]=='addedocivil')
	{
		mysqli_query($conn, "INSERT INTO edocivil (EdoCivil)
									VALUES ('".strtoupper($_POST["edocivil"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el estado civil '.$_POST["edocivil"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '65', '".json_encode($_POST)."')");
	}
    //editar edocivil
    if($_POST["hacer"]=='editedocivil')
    {
        mysqli_query($conn, "UPDATE edocivil SET EdoCivil='".strtoupper($_POST["edocivil"])."'
                                            WHERE Id='".$_POST["idedocivil"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el estado civil '.$_POST["edocivil"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '66', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(65, '<a href="#" onclick="add_edocivil()" class="liga">Nuevo estado civil</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Estado Civil</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Estado Civil</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResEC=mysqli_query($conn, "SELECT * FROM edocivil ORDER BY EdoCivil ASC");
while($RResEC=mysqli_fetch_array($ResEC))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEC["EdoCivil"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(66, '<a href="#" onclick="edit_edocivil(\''.$RResEC["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_edocivil(\''.$RResEC["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '64')");

?>
<script>
//funciones ajax
function add_edocivil(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/edocivil/add_edocivil.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_edocivil(edocivil){
    $.ajax({
				type: 'POST',
				url : 'configuracion/edocivil/edit_edocivil.php',
                data: 'edocivil=' + edocivil
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_edocivil(edocivil){
    $.ajax({
				type: 'POST',
				url : 'configuracion/edocivil/del_edocivil.php',
                data: 'poblacion=' + poblacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}