<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar diagnostico
	if($_POST["hacer"]=='adddiagnostico')
	{
		mysqli_query($conn, "INSERT INTO diagnosticos (Diagnostico)
									VALUES ('".strtoupper($_POST["diagnostico"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el diagnostico '.$_POST["diagnostico"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '87', '".json_encode($_POST)."')");
	}
    //editar diagnostico
    if($_POST["hacer"]=='editdiagnostico')
    {
        mysqli_query($conn, "UPDATE diagnosticos SET Diagnostico='".strtoupper($_POST["diagnostico"])."'
                                            WHERE Id='".$_POST["iddiagnostico"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el diagnostico '.$_POST["diagnostico"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '88', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(87, '<a href="#" onclick="add_diagnostico()" class="liga">Nuevo diagnostico</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Diagnosticos</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Diagnostico</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResDi=mysqli_query($conn, "SELECT * FROM diagnosticos ORDER BY Diagnostico ASC");
while($RResDi=mysqli_fetch_array($ResDi))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.utf8_encode($RResDi["Diagnostico"]).'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(88, '<a href="#" onclick="edit_diagnostico(\''.$RResDi["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_diagnostico(\''.$RResDi["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '86')");
?>
<script>
//funciones ajax
function add_diagnostico(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/diagnosticos/add_diagnostico.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_diagnostico(diagnostico){
    $.ajax({
				type: 'POST',
				url : 'configuracion/diagnosticos/edit_diagnostico.php',
                data: 'diagnostico=' + diagnostico
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_diagnostico(diagnostico){
    $.ajax({
				type: 'POST',
				url : 'configuracion/diagnosticos/del_diagnostico.php',
                data: 'diagnostico=' + diagnostico
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}