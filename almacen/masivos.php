<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');


if(isset($_POST["partida"]))
{
    mysqli_query($conn, "DELETE FROM masivos_temp WHERE Id='".$_POST["partida"]."'");
}

if(isset($_POST["movimiento"]))
{
    //origen
    $ResO=mysqli_query($conn, "SELECT Id FROM origen WHERE Nombre='".$_POST["origen"]."' LIMIT 1");
    if(mysqli_num_rows($ResO)>0)
    {
        $RResO=mysqli_fetch_array($ResO);
    }
    else
    {
        mysqli_query($conn, "INSERT INTO origen (Nombre) VALUES ('".$_POST["origen"]."')");

        $RResO=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM origen WHERE Nombre='".$_POST["origen"]."' LIMIT 1"));
    }

    //caducidad
    if($_POST["caducidad"]==''){$caducidad='1900-01-01';}
    else{$caducidad=$_POST["caducidad"].'-01';}

    mysqli_query($conn, "INSERT INTO masivos_temp (Movimiento, Almacen, Producto, Cantidad, Caducidad, Observaciones, Fecha, Origen, Destino)
                                            VALUES ('".$_POST["movimiento"]."', '".$_POST["almacen"]."', '".$_POST["productos"]."', 
                                                    '".$_POST["cantidad"]."', '".$caducidad."', '".$_POST["observaciones"]."', '".$_POST["fecha"]."', 
                                                    '".$_POST["origen"]."', '".$_POST["destino"]."')") or die(mysqli_error($conn));
}


$cadena='<form name="fadmasivo" id="fadmasivo">
        <table style="width:100%">
            <thead>
                <tr>
                    <th colspan="10" align="center" class="textotitable">Carga masiva de inventario</th>
                </tr>
                <tr>
                    <th align="center" class="textotitable">Movimiento</th>
                    <th align="center" class="textotitable">Almacen</th>
                    <th align="center" class="textotitable">Producto</th>
                    <th align="center" class="textotitable">Cantidad</th>
                    <th align="center" class="textotitable">Caducidad</th>
                    <th align="center" class="textotitable">Origen</th>
                    <th align="center" class="textotitable">Destino</th>
                    <th align="center" class="textotitable">Observaciones</th>
                    <th align="center" class="textotitable">Fecha</th>
                    <th align="center" class="textotitable"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="movimiento" id="movimiento">
                            <option value="0">Seleccione</option>
                            <option value="E">Entrada</option>  
                            <option value="S">Salida</option>
                            <option value="I">Inventario Inicial</option>
                        </select>
                    </td>
                    <td>
                        <select name="almacen" id="almacen">
                            <option value="0">Seleccione</option>';
$ResAlmacenes=mysqli_query($conn, "SELECT * FROM almacenes ORDER BY Nombre ASC");
while($RResA=mysqli_fetch_array($ResAlmacenes))
{
$cadena.='                  <option value="'.$RResA["Id"].'">'.$RResA["Nombre"].'</option>';
}
$cadena.='              </select>
                    </td>
                    <td>
                        <select name="productos" id="productos" onchange="poncaducidad(document.getElementById(\'movimiento\').value, document.getElementById(\'almacen\').value, this.value)">
                            <option value="0">Selecciona</option>';
$ResProductos=mysqli_query($conn, "SELECT * FROM productos ORDER BY Nombre ASC");
while($RResPr=mysqli_fetch_array($ResProductos))
{
    $ResPre=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM presentaciones WHERE Id='".$RResPr["Presentacion"]."' LIMIT 1"));

    $cadena.='              <option value="'.$RResPr["Id"].'">'.$RResPr["Nombre"].' - '.$ResPre["Nombre"];if($RResPr["Volumen"]!=NULL){$cadena.=' - '.$RResPr["Volumen"];}$cadena.='</option>';
}
$cadena.='              </select>
                    </td>
                    <td>
                        <input type="number" name="cantidad" id="cantidad">
                    </td>
                    <td>
                        <div id="d_caducidad">
                            <input type="month" name="caducidad" id="caducidad">
                        </div>
                    </td>
                    <td>
                        <input list="origenl" name="origen" id="origen">
                        <datalist id="origenl">';
$ResOrigen=mysqli_query($conn, "SELECT Origen FROM productos_inventario GROUP BY Origen ORDER BY Origen ASC");
while($RResO=mysqli_fetch_array($ResOrigen))
{
$cadena.='                  <option value"'.$RResO["Origen"].'">';
}
$cadena.='              </datalist>
                    </td>
                    <td>
                        <input list="destinol" name="destino" id="origen">
                        <datalist id="destinol">';
$ResDestino=mysqli_query($conn, "SELECT Destino FROM productos_inventario GROUP BY Destino ORDER BY Destino ASC");
while($RResD=mysqli_fetch_array($ResDestino))
{
$cadena.='                  <option value"'.$RResD["Destino"].'">';
}
$cadena.='              </datalist>
                    </td>
                    <td>
                        <input type="text" name="observaciones" id="observaciones">
                    </td>
                    <td>
                        <input type="date" name="fecha" id="fecha" value="'.date("Y-m-d").'">
                    </td>
                    <td>
                        <input type="submit" name="botadprod" id="botadprod" value="+" onclick="add_producto()">
                    </td>
                </tr>';
$bgcolor="#CCCCCC"; $J=1;
$ResMasivos=mysqli_query($conn, "SELECT * FROM masivos_temp ORDER BY Id DESC");
while($RResM=mysqli_fetch_array($ResMasivos))
{
    switch($RResM["Movimiento"])
    {
        case 'E': $mov='Entrada'; break;
        case 'S': $mov='Salida'; break;
        case 'I': $mov='Inventario Inicial'; break;
    }

    $ResAlmacen=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre FROM almacenes WHERE Id='".$RResM["Almacen"]."' LIMIT 1"));
    $ResProducto=mysqli_fetch_array(mysqli_query($conn, "SELECT p.Nombre, pr.Nombre AS Presentacion, p.Volumen FROM productos AS p
                                                        INNER JOIN presentaciones AS pr ON pr.Id=p.Presentacion
                                                        WHERE p.Id='".$RResM["Producto"]."'"));
    $producto=$ResProducto["Nombre"].' - '.$ResProducto["Presentacion"];if($ResProducto["Volumen"]!=NULL){$producto.=' - '.$ResProducto["Volumen"];}

    if($RResM["Caducidad"]=='1900-01-01'){$caducidad='---';}
    else{$caducidad=caducidad($RResM["Caducidad"]);}

    $cadena.='<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$mov.'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$ResAlmacen["Nombre"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$producto.'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResM["Cantidad"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$caducidad.'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResM["Origen"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResM["Destino"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResM["Observaciones"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResM["Fecha"].'</td>
                <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle"><a href="#" onclick="delete_partida(\''.$RResM["Id"].'\')"><i class="fas fa-trash"></i></a></td>
            </tr>';

    if($bgcolor=="#CCCCCC"){$bgcolor="#FFFFFF";}
    elseif($bgcolor=="#FFFFFF"){$bgcolor="#CCCCCC";}

    $J++;
}
$cadena.='  <tr style="background: '.$bgcolor.'">
                <td colspan="10"><input type="button" name="botguardarmov" id="botguardarmov" value="Guardar >>" onclick="guardar_masivos()"></td>
            </tr>    
            </tbody>
        </table>
        </form>';

echo $cadena;

?>
<script>
function delete_partida(id)
{
    $.ajax({
				type: 'POST',
				url : 'almacen/masivos.php',
                data: 'partida=' + id 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function guardar_masivos()
{
    $.ajax({
				type: 'POST',
				url : 'almacen/almacen.php',
                data: 'hacer=gm' 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
function poncaducidad(movimiento, almacen, producto)
{
    $.ajax({
				type: 'POST',
				url : 'almacen/poncaducidad.php',
                data: 'movimiento=' + movimiento + "&almacen=" + almacen + "&producto=" + producto 
	}).done (function ( info ){
		$('#d_caducidad').html(info);
	});
}

$("#fadmasivo").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadmasivo"));

	$.ajax({
		url: "almacen/masivos.php",
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
</script>   