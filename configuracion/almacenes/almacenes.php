<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar almacen
	if($_POST["hacer"]=='addalmacen')
	{
		mysqli_query($conn, "INSERT INTO almacenes (Nombre)
									VALUES ('".strtoupper($_POST["almacen"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el almacen '.$_POST["almacen"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '114', '".json_encode($_POST)."')");
	}
    //editar almacen
    if($_POST["hacer"]=='editalmacen')
    {
        mysqli_query($conn, "UPDATE almacenes SET Nombre='".strtoupper($_POST["almacen"])."'
                                            WHERE Id='".$_POST["idalmacen"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el almacen '.$_POST["almacen"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '115', '".json_encode($_POST)."')");
    }
    //borrar almacen
    if($_POST["hacer"]=='delalmacen')
    {
        $ResAlmacen=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM almacenes WHERE Id='".$_POST["almacen"]."' LIMIT 1"));

        mysqli_query($conn, "DELETE FROM almacenes WHERE Id='".$_POST["almacen"]."'") OR die(mysqli_error($conn));

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se elimino el almacen '.$ResAlmacen["Nombre"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '136', '".json_encode($_POST)."')");
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(114, '<a href="#" onclick="add_almacen()" class="liga">Nuevo Almacén</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Almacenes</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Almacén</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResAlmacenes=mysqli_query($conn, "SELECT * FROM almacenes ORDER BY Nombre ASC");
while($RResAlm=mysqli_fetch_array($ResAlmacenes))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResAlm["Nombre"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(115, '<a href="#" onclick="edit_almacen(\''.$RResAlm["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(136, '<a href="#" onclick="delete_almacen(\''.$RResAlm["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>').'
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '113')");

?>
<script>
//funciones ajax
function add_almacen(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/almacenes/add_almacen.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_almacen(almacen){
    $.ajax({
				type: 'POST',
				url : 'configuracion/almacenes/edit_almacen.php',
                data: 'almacen=' + almacen
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_almacen(almacen){
    $.ajax({
				type: 'POST',
				url : 'configuracion/almacenes/del_almacen.php',
                data: 'almacen=' + almacen
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>