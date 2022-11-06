<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar religion
	if($_POST["hacer"]=='addreligion')
	{
		mysqli_query($conn, "INSERT INTO religion (Religion)
									VALUES ('".strtoupper($_POST["religion"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la religion '.$_POST["religion"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '59', '".json_encode($_POST)."')");
	}
    //editar religion
    if($_POST["hacer"]=='editreligion')
    {
        mysqli_query($conn, "UPDATE religion SET Religion='".strtoupper($_POST["religion"])."'
                                            WHERE Id='".$_POST["idreligion"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la religion '.$_POST["religion"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '60', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(59, '<a href="#" onclick="add_religion()" class="liga">Nueva religion</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Religiones</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Religion</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResReligion=mysqli_query($conn, "SELECT * FROM religion ORDER BY Religion ASC");
while($RResRel=mysqli_fetch_array($ResReligion))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResRel["Religion"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(60, '<a href="#" onclick="edit_religion(\''.$RResRel["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_religion(\''.$RResRel["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '58')");

?>
<script>
//funciones ajax
function add_religion(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/religion/add_religion.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_religion(religion){
    $.ajax({
				type: 'POST',
				url : 'configuracion/religion/edit_religion.php',
                data: 'religion=' + religion
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_religion(poblacion){
    $.ajax({
				type: 'POST',
				url : 'configuracion/religion/del_religion.php',
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