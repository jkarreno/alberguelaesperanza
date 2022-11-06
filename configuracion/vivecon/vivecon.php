<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar vivecon
	if($_POST["hacer"]=='addvivecon')
	{
		mysqli_query($conn, "INSERT INTO vivecon (ViveCon)
									VALUES ('".strtoupper($_POST["vivecon"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el parametro '.$_POST["vivecon"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '74', '".json_encode($_POST)."')");
	}
    //editar vivecon
    if($_POST["hacer"]=='editvivecon')
    {
        mysqli_query($conn, "UPDATE vivecon SET ViveCon='".strtoupper($_POST["vivecon"])."'
                                            WHERE Id='".$_POST["idvivecon"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el parametro '.$_POST["vivecon"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '75', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(74, '<a href="#" onclick="add_vivecon()" class="liga">Nueva vivecon</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Vive Con</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Vive con</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResViv=mysqli_query($conn, "SELECT * FROM vivecon ORDER BY ViveCon ASC");
while($RResViv=mysqli_fetch_array($ResViv))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResViv["ViveCon"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(75, '<a href="#" onclick="edit_vivecon(\''.$RResViv["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_vivecon(\''.$RResViv["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '73')");

?>
<script>
//funciones ajax
function add_vivecon(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/vivecon/add_vivecon.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_vivecon(vivecon){
    $.ajax({
				type: 'POST',
				url : 'configuracion/vivecon/edit_vivecon.php',
                data: 'vivecon=' + vivecon
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_vivecon(vivecon){
    $.ajax({
				type: 'POST',
				url : 'configuracion/vivecon/del_vivecon.php',
                data: 'poblacion=' + poblacion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}