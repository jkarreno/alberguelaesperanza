<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

//Estatus 1-confirmado || 2-cancelado 
//pagada 0-no pagada || 1- parcialmente pagada ||2- totalmente pagada

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar producto
	if($_POST["hacer"]=='addproducto')
	{
        mysqli_query($conn, "INSERT INTO productos (Nombre, Categoria, Presentacion, Volumen, Marca, Observaciones)
                                            VALUES ('".strtoupper($_POST["nombre"])."', '".$_POST["categoria"]."', '".$_POST["presentacion"]."', 
                                                    '".$_POST["volumen"]."', '".$_POST["marca"]."', 
                                                    '".$_POST["observaciones"]."')");

        //bitacora
        $arr = array('nombre' => strtoupper($_POST["nombre"]), 'presentacion' => $_POST["presentacion"], 'volumen' => $_POST["volumen"], 'marca' => $_POST["marca"], 'observaciones' => $_POST["observaciones"], 'hacer' => $_POST["hacer"]);
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '120', '".json_encode($arr)."')");

        if($_POST["inventarioi"]>0)
        {
            $IdProducto=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM productos ORDER BY Id DESC LIMIT 1"));

            if($_POST["caducidad"]==''){$caducidad='1900-01-01';}
            else{$caducidad=$_POST["caducidad"].'-01';}

            mysqli_query($conn, "INSERT INTO productos_inventario (IdProducto, IdAlmacen, Movimiento, Cantidad, Stock, Fecha, Caducidad, Origen)
                                                            VALUES ('".$IdProducto["Id"]."', '".$_POST["almacen"]."', 'I', '".$_POST["inventarioi"]."',
                                                                    '".$_POST["inventarioi"]."', '".date("Y-m-d")."', '".$caducidad."', '".strtoupper($_POST["origen"])."')") or die(mysqli_error($conn));

             //bitacora
            $arr2 = array('idproducto' => $IdProducto["Id"], 'cantidad' => $_POST["inventarioi"], 'almacen' => $_POST["almacen"], 'movimiento' => 'I', 'caducidad' => $_POST["caducidad"], 'origen'=>strtoupper($_POST["origen"]), 'hacer' => $_POST["hacer"]);
            mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '121', '".json_encode($arr2)."')");
        }
        
    }
    //agregar masivos
    if($_POST["hacer"]=='gm')
    {
        $arraym=array(); $A=1; $m='';
        $ResMasivos=mysqli_query($conn, "SELECT * FROM masivos_temp ORDER BY Id ASC");
        while($RResM=mysqli_fetch_array($ResMasivos))
        {
            //calcular stock
            $ResS=mysqli_fetch_array(mysqli_query($conn, "SELECT Stock FROM productos_inventario WHERE IdProducto='".$RResM["Producto"]."' AND IdAlmacen='".$RResM["Almacen"]."' ORDER BY Fecha DESC, Id DESC LIMIT 1"));

            if($ResS["Stock"]==NULL){$stock=$RResM["Cantidad"];}
            else
            {
                if($RResM["Movimiento"]=='E'){$stock=$ResS["Stock"]+$RResM["Cantidad"];}
                elseif($RResM["Movimiento"]=='S'){$stock=$ResS["Stock"]-$RResM["Cantidad"];}
                elseif($RResM["Movimiento"]=='I'){$stock=$RResM["Cantidad"];}
            }
            

            mysqli_query($conn, "INSERT INTO productos_inventario (IdProducto, IdAlmacen, Movimiento, Cantidad, Stock, Fecha, Caducidad, Origen, Destino, Observaciones)
                                                            VALUES ('".$RResM["Producto"]."', '".$RResM["Almacen"]."', '".$RResM["Movimiento"]."', '".$RResM["Cantidad"]."', 
                                                                    '".$stock."', '".$RResM["Fecha"]."', '".$RResM["Caducidad"]."', '".strtoupper($RResM["Origen"])."', '".strtoupper($RResM["Destino"])."', 
                                                                    '".strtoupper($RResM["Observaciones"])."')") or die(mysqli_error($conn));

            $arreglo=array($A, 'IdProducto' => $RResM["Producto"], 'IdAlmacen' => $RResM["Almacen"], 'Movimiento' => $RResM["Movimiento"], 'Cantidad' => $RResM["Cantidad"],
                            'Stock' => $stock, 'Fecha' => $RResM["Fecha"], 'Caducidad' => $RResM["Caducidad"], 'Origen' => strtoupper($RResM["Origen"]), 'Destino' => strtoupper($RResM["Destino"]),
                            'Observaciones' => strtoupper($RResM["Observaciones"]));
            array_push($arraym, $arreglo);

            //borra de temporales
            mysqli_query($conn, "DELETE FROM masivos_temp WHERE Id='".$RResM["Id"]."'");

            $A++;
            $m.="SELECT Stock FROM productos_inventario WHERE IdProducto='".$RResM["Producto"]."' AND IdAlmacen='".$RResM["Almacen"]."' ORDER BY Fecha DESC, Id DESC LIMIT 1";
        }

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '132', '".json_encode($arraym)."')");

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se modificaron los datos del producto</div>';
    }
}

$cadena=$mensaje.'<table style="width:80%">
            <thead>
            <tr>
                <td colspan="3">
                    <select name="productos" id="productos" onchange="inv_producto(this.value)">
                        <option value="0">Selecciona</option>';
$ResProductos=mysqli_query($conn, "SELECT * FROM productos ORDER BY Nombre ASC");
while($RResPr=mysqli_fetch_array($ResProductos))
{
    $cadena.=permisos(122, '          <option value="'.$RResPr["Id"].'">'.strtoupper(utf8_encode($RResPr["Nombre"])).' - '.$RResPr["Volumen"].'</option>');
}
$cadena.='          </select>
                </td>
                <td colspan="2" style="text-align: right">| '.permisos(132, '<a href="#" onclick="masivos()" class="liga">Masivos</a>').' | '.permisos(120, '<a href="#" onclick="add_producto()" class="liga">Nuevo producto</a>').' | <a href="almacen/existencias_excel.php" target="_blank"><i class="fa-solid fa-file-excel"></i></a> | <a href="almacen/existencias_pdf.php" target="_blank"><i class="fa-solid fa-file-pdf"></i></a></td>
            </tr>
            <tr>
                <th colspan="5" align="center" class="textotitable">Productos</td>
            </tr>
            <tr>
                <th align="center" class="textotitable">&nbsp;</th>
                <th align="center" class="textotitable">Producto</th>
                <th align="center" class="textotitable">Presentación</th>
                <th align="center" class="textotitable">Almacen</th>
                <th align="center" class="textotitable">Existencia</th>
            </tr>
            </thead>
            <tbody>';
$ResProductos=mysqli_query($conn, "SELECT * FROM productos ORDER BY Nombre ASC");
$J=1; $bgcolor="#ffffff";
while($RResP=mysqli_fetch_array($ResProductos))
{
    $ResPr=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM presentaciones WHERE Id='".$RResP["Presentacion"]."' LIMIT 1"));

    $ResAlmacenes=mysqli_query($conn, "SELECT * FROM almacenes ORDER BY Nombre ASC");
    while($RResA=mysqli_fetch_array($ResAlmacenes))
    {
        $ResInventario=mysqli_query($conn, "SELECT Stock FROM productos_inventario WHERE IdProducto='".$RResP["Id"]."' AND IdAlmacen='".$RResA["Id"]."' AND Stock > 0 ORDER BY Fecha DESC, Id DESC LIMIT 1");
        if(mysqli_num_rows($ResInventario)>0)
        {
            while($RResI=mysqli_fetch_array($ResInventario))
            {
                $cadena.=permisos(122, '<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle"><a href="#" onclick="inv_producto(\''.$RResP["Id"].'\')">'.strtoupper(utf8_encode($RResP["Nombre"])).' - '.$RResP["Volumen"].'</a></td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$ResPr["Nombre"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResA["Nombre"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResI["Stock"].'</td>
                </tr>');

                $J++;
                if($bgcolor=="#ffffff"){$bgcolor="#cccccc";}
                elseif($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
            }
        }
    }

    

}
//$ResStock=mysqli_query($conn, "SELECT * FROM productos_inventario ORDER BY Fecha DESC, Id DESC LIMIT 100");
//
//while($RResS=mysqli_fetch_array($ResStock))
//{
//    $ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM productos WHERE Id='".$RResS["IdProducto"]."' LIMIT 1"));
//    $ResA=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM almacenes WHERE Id='".$RResS["IdAlmacen"]."' LIMIT 1"));
//    $ResPr=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM presentaciones WHERE Id='".$ResP["Presentacion"]."' LIMIT 1"));
//
//    switch($RResS["Movimiento"])
//    {
//        case 'I': $mov='Inventario'; $s='<i class="fas fa-plus" style="font-size: 16px;"></i>'; $estilo='#26b719'; break;
//        case 'E': $mov='Entrada'; $s='<i class="fas fa-plus" style="font-size: 16px;"></i>'; $estilo='#26b719'; break;
//        case 'S': $mov='Salida'; $s='<i class="fas fa-minus" style="font-size: 16px;"></i>'; $estilo='#ff0000'; break;
//    }
//
//    if($RResS["Caducidad"]=='1900-01-01'){$caducidad='---';}
//    else{$caducidad=caducidad($RResS["Caducidad"]);}
//
//    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
//                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
//                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$ResP["Nombre"].'</td>
//                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResA["Nombre"].'</td>
//                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResPr["Nombre"].'</td>
//                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$mov.'</td>
//                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle" style="color: '.$estilo.'">'.$s.$RResS["Cantidad"].'</td>
//                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResS["Stock"].'</td>
//                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$caducidad.'</td>
//                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResS["Observaciones"].'</td>
//                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.fechados($RResS["Fecha"]).'</td>
//            </tr>';
//
//    $J++;
//    
//    
//}

$cadena.='  </tbody>
        </table>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo) VALUES ('".time()."', '".$_SESSION["Id"]."', '119')");

?>
<script>
function add_producto(){
	$.ajax({
				type: 'POST',
				url : 'almacen/add_producto.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function inv_producto(producto){
    $.ajax({
				type: 'POST',
				url : 'almacen/producto.php',
                data: 'producto=' + producto
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function masivos(){
    $.ajax({
				type: 'POST',
				url : 'almacen/masivos.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>