<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar instituto
	if($_POST["hacer"]=='addinstituto')
	{
		mysqli_query($conn, "INSERT INTO institutos (Instituto)
									VALUES ('".$_POST["instituto"]."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el instituto '.$_POST["instituto"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '56', '".json_encode($_POST)."')");
	}
    //editar instituto
    if($_POST["hacer"]=='editinstituto')
    {
        mysqli_query($conn, "UPDATE institutos SET Instituto='".$_POST["instituto"]."'
                                            WHERE Id='".$_POST["idinstituto"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el instituto '.$_POST["instituto"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '57', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(56, '<a href="#" onclick="add_instituto()" class="liga">Nuevo Instituto</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Institutos</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Instituto</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResInstitutos=mysqli_query($conn, "SELECT * FROM institutos ORDER BY Instituto ASC");
while($RResInst=mysqli_fetch_array($ResInstitutos))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResInst["Instituto"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(57, '<a href="#" onclick="edit_instituto(\''.$RResInst["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_instituto(\''.$RResInst["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '55')");

?>
<script>
//funciones ajax
function add_instituto(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/institutos/add_instituto.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_instituto(instituto){
    $.ajax({
				type: 'POST',
				url : 'configuracion/institutos/edit_instituto.php',
                data: 'instituto=' + instituto
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