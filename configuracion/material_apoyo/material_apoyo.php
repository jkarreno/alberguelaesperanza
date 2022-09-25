<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../../conexion.php');
include('../../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar material de apoyo
	if($_POST["hacer"]=='addmaterial')
	{
		mysqli_query($conn, "INSERT INTO material_apoyo (Nombre)
									VALUES ('".$_POST["material"]."')") or die(mysqli_error($conn));

		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el material '.$_POST["material"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '96', '".json_encode($_POST)."')");
	}
    //editar material de apoyo
    if($_POST["hacer"]=='editmaterial')
    {
        mysqli_query($conn, "UPDATE material_apoyo SET Nombre='".$_POST["material"]."'
                                            WHERE Id='".$_POST["idmaterial"]."'");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el material '.$_POST["material"].'</div>';

        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '97', '".json_encode($_POST)."')");
    }
    //agregar inventario inicial
    if($_POST["hacer"]=='ii_material')
    {
        mysqli_query($conn, "INSERT INTO material_apoyo_inventario (Fecha, IdReservacion, IdPA, PA, ES, IdMaterial, Cantidad, Usuario)
                                                        VALUES ('".date("Y-m-d")."', '0', '0', 'I', 'E', '".$_POST["idmaterial"]."', '".$_POST["cant_material"]."', '".$_SESSION["Id"]."')") or die(mysqli_error($conn));

        $mat=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM material_apoyo WHERE Id='".$_POST["idmateriala"]."' LIMIT 1"));

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el inventario de '.$mat["Nombre"].'</div>';
        
        //bitacora
		mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '98', '".json_encode($_POST)."')");                                                
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="5" style="text-align: right">| '.permisos(96, '<a href="#" onclick="add_material()" class="liga">Nuevo material</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="5" align="center" class="textotitable">Material de Asistencia</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Prenda</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
$bgcolor="#ffffff"; $J=1;
$ResMat=mysqli_query($conn, "SELECT * FROM material_apoyo ORDER BY Nombre ASC");
while($RResMat=mysqli_fetch_array($ResMat))
{
    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.utf8_encode($RResMat["Nombre"]).'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(97, '<a href="#" onclick="edit_material(\''.$RResMat["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(98, '<a href="#" onclick="inv_material(\''.$RResMat["Id"].'\')"><i class="fas fa-boxes" aria-hidden="true"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="#" onclick="delete_prenda(\''.$RResMat["Id"].'\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '95')");

?>
<script>
//funciones ajax
function add_material(){
	$.ajax({
				type: 'POST',
				url : 'configuracion/material_apoyo/add_material.php'
	}).done (function ( info ){
		$('#contenido2').html(info);
	});

}

function edit_material(material){
    $.ajax({
				type: 'POST',
				url : 'configuracion/material_apoyo/edit_material.php',
                data: 'material=' + material
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function delete_prenda(prenda){
    $.ajax({
				type: 'POST',
				url : 'configuracion/lavanderia/del_prenda.php',
                data: 'prenda=' + prenda
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

function inv_material(material){
    $.ajax({
				type: 'POST',
				url : 'configuracion/material_apoyo/inventario_inicial.php',
                data: 'material=' + material
	}).done (function ( info ){
		$('#contenido2').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>