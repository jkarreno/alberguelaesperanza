<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar escolaridad
	if($_POST["hacer"]=='addescolaridad')
	{
		mysqli_query($conn, "INSERT INTO escolaridad (Escolaridad)
									VALUES ('".$_POST["escolaridad"]."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la escolaridad '.$_POST["escolaridad"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '68', '".json_encode($_POST)."')");
	}
    //editar escolaridad
    if($_POST["hacer"]=='editescolaridad')
    {
        mysqli_query($conn, "UPDATE escolaridad SET Escolaridad='".$_POST["escolaridad"]."'
                                            WHERE Id='".$_POST["idescolaridad"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la escolaridad '.$_POST["escolaridad"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '69', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(68, '<a href="#" onclick="add_escolaridad()" class="liga">Nueva escolaridad</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Escolaridad</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Escolaridad</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResEsc=mysqli_query($conn, "SELECT * FROM escolaridad ORDER BY Escolaridad ASC");
while($RResEsc=mysqli_fetch_array($ResEsc))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResEsc["Escolaridad"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(69, '<a href="#" onclick="edit_escolaridad(\''.$RResEsc["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_escolaridad(\''.$RResEsc["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '67')");

?>
<script>
//funciones ajax
function add_escolaridad(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/escolaridad/add_escolaridad.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_escolaridad(escolaridad){
    $.ajax({
				type: 'POST',
				url : 'configuracion/escolaridad/edit_escolaridad.php',
                data: 'escolaridad=' + escolaridad
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_escolaridad(escolaridad){
    $.ajax({
				type: 'POST',
				url : 'configuracion/escolaridad/del_escolaridad.php',
                data: 'poblacion=' + poblacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}