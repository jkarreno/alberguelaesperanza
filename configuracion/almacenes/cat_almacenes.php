<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar categoria almacen
	if($_POST["hacer"]=='addcatalmacen')
	{
		mysqli_query($conn, "INSERT INTO cat_almacenes (Nombre)
									VALUES ('".strtoupper($_POST["catalmacen"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la categoría '.$_POST["catalmacen"].' de almacenes </div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '130', '".json_encode($_POST)."')");
	}
    //editar categoria almacen
    if($_POST["hacer"]=='editcatalmacen')
    {
        mysqli_query($conn, "UPDATE cat_almacenes SET Nombre='".strtoupper($_POST["catalmacen"])."'
                                            WHERE Id='".$_POST["idcatalmacen"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la categoría '.$_POST["catalmacen"].' de almacenes</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '131', '".json_encode($_POST)."')");
    }
}



$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="5" style="text-align: right">| '.permisos(130, '<a href="#" onclick="add_cat_almacen()" class="liga">Nueva Categoría</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="5" align="center" class="textotitable">Categorias almacenes</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Categoría</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResCatAlmacenes=mysqli_query($conn, "SELECT * FROM cat_almacenes WHERE Categoria='0' ORDER BY Nombre ASC");
while($RResCAlm=mysqli_fetch_array($ResCatAlmacenes))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResCAlm["Nombre"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(133, '<a href="#" onclick="sub_cat_almacen(\''.$RResCAlm["Id"].'\')"><i class="fas fa-boxes"></i></a>').'
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(131, '<a href="#" onclick="edit_cat_almacen(\''.$RResCAlm["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="#" onclick="delete_cat_almacen(\''.$RResCAlm["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '129')");

?>
<script>
//funciones ajax
function add_cat_almacen(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/almacenes/add_cat_almacen.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_cat_almacen(categoria){
    $.ajax({
				type: 'POST',
				url : 'configuracion/almacenes/edit_cat_almacen.php',
                data: 'cat=' + categoria
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function sub_cat_almacen(categoria){
    $.ajax({
				type: 'POST',
				url : 'configuracion/almacenes/sub_cat_almacen.php',
                data: 'cat=' + categoria
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>