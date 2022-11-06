<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResCat=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM cat_almacenes WHERE Id='".$_POST["cat"]."' LIMIT 1"));

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar sub categoria almacen
	if($_POST["hacer"]=='addsubcatalmacen')
	{
		mysqli_query($conn, "INSERT INTO cat_almacenes (Categoria, Nombre)
									VALUES ('".$_POST["cat"]."', '".strtoupper($_POST["subcatalmacen"])."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego la subcategoría '.$_POST["subcatalmacen"].' </div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '134', '".json_encode($_POST)."')");
	}
    //editar categoria almacen
    //if($_POST["hacer"]=='editcatalmacen')
    //{
    //    mysqli_query($conn, "UPDATE cat_almacenes SET Nombre='".$_POST["catalmacen"]."'
    //                                        WHERE Id='".$_POST["idcatalmacen"]."'");
//
    //    $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la categoría '.$_POST["catalmacen"].' de almacenes</div>';
//
    //    //bitacora
	//	mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '131', '".json_encode($_POST)."')");
    //}
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: right">| '.permisos(134, '<a href="#" onclick="add_sub_cat_almacen(\''.$ResCat["Id"].'\')" class="liga">Nueva Sub Categoría</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="4" align="center" class="textotitable">Sub Categorias de '.$ResCat["Nombre"].'</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">SubCategoría</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResSubCatAlmacenes=mysqli_query($conn, "SELECT * FROM cat_almacenes WHERE Categoria='".$ResCat["Id"]."' ORDER BY Nombre ASC");
while($RResSCAlm=mysqli_fetch_array($ResSubCatAlmacenes))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResSCAlm["Nombre"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="#" onclick="edit_cat_almacen(\''.$RResSCAlm["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a> 
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="#" onclick="delete_cat_almacen(\''.$RResSCAlm["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '133')");

?>
<script>
//funciones ajax
function add_sub_cat_almacen(categoria){
	$.ajax({
				type: 'POST',
				url : 'configuracion/almacenes/add_sub_cat_almacen.php',
                data: 'cat=' + categoria
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}