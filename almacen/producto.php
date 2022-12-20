<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$mensaje='';

if(isset($_POST["almacen"])){$almacen=$_POST["almacen"];}
else{$almacen='%';}

if(isset($_POST["hacer"]))
{
    if($_POST["hacer"]=='admovimiento')
    {
        $ResStock=mysqli_fetch_array(mysqli_query($conn, "SELECT Stock FROM productos_inventario WHERE IdProducto='".$_POST["producto"]."' AND IdAlmacen='".$_POST["almacen"]."' ORDER BY Fecha DESC, Id DESC LIMIT 1"));
        
        if($_POST["movimiento"]=='I'){$stock=$_POST["cantidad"];}
        elseif($_POST["movimiento"]=='E'){$stock=$ResStock["Stock"]+$_POST["cantidad"];}
        elseif($_POST["movimiento"]=='S'){$stock=$ResStock["Stock"]-$_POST["cantidad"];}

        if($_POST["caducidad"]==''){$caducidad='1900-01-01';}
        else{$caducidad=$_POST["caducidad"].'-01';}

        mysqli_query($conn, "INSERT INTO productos_inventario (IdProducto, IdAlmacen, Movimiento, Cantidad, Stock, Fecha, Caducidad, Origen, Destino, Observaciones)
                                                    VALUES ('".$_POST["producto"]."', '".$_POST["almacen"]."', '".$_POST["movimiento"]."', '".$_POST["cantidad"]."', 
                                                            '".$stock."', '".$_POST["fecha"]."', '".$caducidad."', '".strtoupper($_POST["origen"])."', '".strtoupper($_POST["destino"])."', '".strtoupper($_POST["observaciones"])."')") or die(mysqli_error($conn));

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '123', '".json_encode($_POST)."')");
    }
    if($_POST["hacer"]=='editproducto')
    {
        mysqli_query($conn, "UPDATE productos SET Nombre='".strtoupper($_POST["nombre"])."',
                                                    Categoria='".$_POST["categoria"]."',
                                                    Presentacion='".$_POST["presentacion"]."', 
                                                    Volumen='".$_POST["volumen"]."', 
                                                    Marca='".$_POST["marca"]."', 
                                                    Observaciones='".strtoupper($_POST["observaciones"])."' 
                                            WHERE Id='".$_POST["producto"]."'") or die(mysqli_error($conn));

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se modificaron los datos del producto</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '124', '".json_encode($_POST)."')");
    }
    if($_POST["hacer"]=='delmovimiento')
    {
        if($_POST["borra"]=='no')
        {
            $mensaje='<div class="mesaje"><i class="fas fa-exclamation-triangle"></i>Se eliminara el movimiento '.$_POST["num"].' del producto<br /><a href="#">SI</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">No</a></div>';
        }
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
            <tr>
            <td colspan="5">
                <select name="productos" id="productos" onchange="inv_producto(this.value, document.getElementById(\'almacenb\').value)">
                    <option value="0">Selecciona</option>';
$ResProductos=mysqli_query($conn, "SELECT * FROM productos ORDER BY Nombre ASC");
while($RResPr=mysqli_fetch_array($ResProductos))
{
$cadena.='          <option value="'.$RResPr["Id"].'"';if($RResPr["Id"]==$_POST["producto"]){$cadena.=' selected';}$cadena.='>'.strtoupper(utf8_encode($RResPr["Nombre"])).' - '.$RResPr["Volumen"].'</option>';
}
$cadena.='      </select>
            </td>
            <td colspan="2">
                <select name="almacenb" id="almacenb" onchange="inv_producto(document.getElementById(\'producto\').value, this.value)">
                    <option value="%">Almacenes</option>';
$ResAlmacenes=mysqli_query($conn, "SELECT * FROM almacenes ORDER BY Nombre ASC");
while($RResAl=mysqli_fetch_array($ResAlmacenes))
{
    $cadena.='      <option value="'.$RResAl["Id"].'">'.$RResAl["Nombre"].'</option>';
}
$cadena.='      </select>
            </td>
            <td colspan="3" style="text-align: right">| '.permisos(124, '<a href="#" onclick="edit_producto(\''.$_POST["producto"].'\')" class="liga">Editar producto</a>').' |</td>
        </tr>
        <tr>
            <td colspan="11">
                <div class="c100 card" style="border: 0;box-shadow: none;margin: 0;padding: 0;">
                    <form name="fadmovinv" id="fadmovinv">
                        <div class="c20">
                            <label class="l_form">Movimiento</label>
                            <select name="movimiento" id="movimiento">
                                <option value="0">Seleccione</option>
                                <option value="E">Entrada</option>  
                                <option value="S">Salida</option>
                                <option value="I">Inventario</option>
                            </select>
                        </div>
                        <div class="c20">
                            <label class="l_form">Almacén</label>
                            <select name="almacen" id="almacen">
                                <option value="0">Seleccione</option>';
$ResAlmacenes=mysqli_query($conn, "SELECT * FROM almacenes ORDER BY Nombre ASC");
while($RResA=mysqli_fetch_array($ResAlmacenes))
{
    $cadena.='                  <option value="'.$RResA["Id"].'"';if($RREsA["Id"]==$_POST["almacen"]){$cadena.=' selected';}$cadena.='>'.$RResA["Nombre"].'</option>';
}
$cadena.='                  </select>
                        </div>
                        <div class="c20">
                            <label class="l_form">Cantidad</label>
                            <input type="number" name="cantidad" id="cantidad" value="0">
                        </div>
                        <div class="c20">
                            <label class="l_form">Fecha:</label>
                            <input type="date" name="fecha" id="fecha" value="'.date("Y-m-d").'">
                        </div>

                        <div class="c30">
                            <label class="l_form">Caducidad</label>
                            <input type="month" name="caducidad" id="caducidad">
                        </div>
                        <div class="c30">
                            <label class="l_form">Origen</label>
                            <input list="origenl" name="origen" id="origen">
                            <datalist id="origenl">';
$ResOrigen=mysqli_query($conn, "SELECT Origen FROM productos_inventario GROUP BY Origen ORDER BY Origen ASC");
while($RResO=mysqli_fetch_array($ResOrigen))
{
    $cadena.='                  <option value"'.$RResO["Origen"].'">';
}
$cadena.='                  </datalist>
                        </div>
                        <div class="c30">
                            <label class="l_form">Destino</label>
                            <input list="destinol" name="destino" id="origen">
                            <datalist id="destinol">';
$ResDestino=mysqli_query($conn, "SELECT Destino FROM productos_inventario GROUP BY Destino ORDER BY Destino ASC");
while($RResD=mysqli_fetch_array($ResDestino))
{
    $cadena.='                  <option value"'.$RResD["Destino"].'">';
}
$cadena.='                  </datalist>
                        </div>


                        <div class="c60">
                            <label class="l_form">Observaciones</label>
                            <input type="text" name="observaciones" id="observaciones">
                        </div>
                        <div clas="c30">
                            <input type="hidden" name="producto" id="producto" value="'.$_POST["producto"].'">
                            <input type="hidden" name="hacer" id="hacer" value="admovimiento">
                            '.permisos(123, '<input type="submit" name="botadmov" id="botadmov" value="Guardar>>">').'
                        </div>
                    </form>
                </div>
            </td>    
        </tr>
        <tr>
            <td colspan="11" align="right"><a href="almacen/producto_excel.php?producto='.$_POST["producto"].'&almacen='.$almacen.'" download><i class="fa-solid fa-file-excel"></i></a> | <a href="almacen/producto_pdf.php?producto='.$_POST["producto"].'&almacen='.$almacen.'" target="_blank"><i class="fa-solid fa-file-pdf"></i></a></td>
        </tr>
        <tr>
            <th colspan="11" align="center" class="textotitable">Productos</td>
        </tr>
        <tr>
            <th align="center" class="textotitable">&nbsp;</th>
            <th align="center" class="textotitable">Producto</th>
            <th align="center" class="textotitable">Almacen</th>
            <th align="center" class="textotitable">Presentación</th>
            <th align="center" class="textotitable">Movimiento</th>
            <th align="center" class="textotitable">Cantidad</th>
            <th align="center" class="textotitable">Stock</th>
            <th align="center" class="textotitable">Caducidad</th>
            <th align="center" class="textotitable">Observaciones</th>
            <th align="center" class="textotitable">Fecha</th>
            <th align="center" class="textotitable">&nbsp;</th>
        </tr>
        </thead>
        <tbody>';
$ResStock=mysqli_query($conn, "SELECT * FROM productos_inventario WHERE IdProducto='".$_POST["producto"]."' AND IdAlmacen LIKE '".$almacen."' ORDER BY Fecha DESC, Id DESC LIMIT 100");
$J=1; $bgcolor="#ffffff";
while($RResS=mysqli_fetch_array($ResStock))
{
    $ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM productos WHERE Id='".$RResS["IdProducto"]."' LIMIT 1"));
    $ResA=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM almacenes WHERE Id='".$RResS["IdAlmacen"]."' LIMIT 1"));
    $ResU=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM presentaciones WHERE Id='".$ResP["Presentacion"]."' LIMIT 1"));

    switch($RResS["Movimiento"])
    {
        case 'I': $mov='Inventario'; $s='<i class="fas fa-plus" style="font-size: 16px;"></i>'; $estilo='#26b719'; break;
        case 'E': $mov='Entrada'; $s='<i class="fas fa-plus" style="font-size: 16px;"></i>'; $estilo='#26b719'; break;
        case 'S': $mov='Salida'; $s='<i class="fas fa-minus" style="font-size: 16px;"></i>'; $estilo='#ff0000'; break;
    }

    if($RResS["Caducidad"]=='1900-01-01'){$caducidad='---';}
    else{$caducidad=caducidad($RResS["Caducidad"]);}

    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.strtoupper(utf8_encode($ResP["Nombre"])).' - '.$ResP["Volumen"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResA["Nombre"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResU["Nombre"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$mov.'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle" style="color: '.$estilo.'">'.$s.$RResS["Cantidad"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResS["Stock"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$caducidad.'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResS["Observaciones"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.fechados($RResS["Fecha"]).'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                    <a href="#" onclick="delete_movimiento(\''.$RResS["Id"].'\', \''.$_POST["producto"].'\', \''.$J.'\')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>';

    $J++;
    if($bgcolor=="#ffffff"){$bgcolor="#cccccc";}
    elseif($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}

$cadena.='  </tbody>
        </table>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '122')");

?>
<script>
function edit_producto(producto){
	$.ajax({
				type: 'POST',
				url : 'almacen/edit_producto.php',
                data: 'producto=' + producto 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function inv_producto(producto, almacen){
    $.ajax({
				type: 'POST',
				url : 'almacen/producto.php',
                data: 'producto=' + producto + '&almacen=' + almacen
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}


$("#fadmovinv").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadmovinv"));

	$.ajax({
		url: "almacen/producto.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#contenido").html(echo);
	});
});

function delete_movimiento (idmovimiento, producto, num){
    $.ajax({
				type: 'POST',
				url : 'almacen/producto.php',
                data: 'producto=' + producto + '&hacer=delmovimiento&borra=no&idmovimiento=' + idmovimiento + '&num=' + num
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>

