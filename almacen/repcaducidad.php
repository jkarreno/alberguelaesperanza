<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

if(isset($_POST["annomes"]))
{
    $cad=$_POST["annomes"];
}
else
{
    $cad=$_POST["anno"].'-'.$_POST["mes"];
}

$cadena='<table style="width:80%">
            <thead>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="2" align="left">
                        <input type="month" name="caducidad" id="caducidad" value="'.$cad.'" onchange="repo_caducidad(this.value)">
                    </td>
                </tr>
                <tr>
                    <th colspan="7" align="center" class="textotitable">Productos</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Producto</th>
                    <th align="center" class="textotitable">Presentación</th>
                    <th align="center" class="textotitable">Volumen</th>
                    <th align="center" class="textotitable">Fecha de Caducidad</th>
                    <th align="center" class="textotitable">Existencia</th>
                    <th align="center" class="textotitable">Almacén</th>
                </tr>
            </thead>
            <tbody>';
$ResProductos=mysqli_query($conn, "SELECT * FROM productos ORDER BY Nombre ASC");
$J=1; $bgcolor="#ffffff";
while($RResP=mysqli_fetch_array($ResProductos))
{
    $ResAlmacen=mysqli_query($conn, "SELECT * FROM almacenes ORDER BY Nombre ASC");
    while($RResA=mysqli_fetch_array($ResAlmacen))
    {
        $ResCad=mysqli_query($conn, "SELECT Id, Caducidad FROM productos_inventario WHERE IdProducto='".$RResP["Id"]."' AND IdAlmacen='".$RResA["Id"]."' AND Caducidad='".$cad."-01' ORDER BY Fecha ASC");

        if(mysqli_num_rows($ResCad)>0)
        {
            $ResPr=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM presentaciones WHERE Id='".$RResP["Presentacion"]."' LIMIT 1"));

            $cadena.=permisos(122, '<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$J.'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle"><a href="#" onclick="inv_producto(\''.$RResP["Id"].'\')">'.strtoupper(utf8_encode($RResP["Nombre"])).' - '.$RResP["Volumen"].'</a></td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$ResPr["Nombre"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResP["Volumen"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.caducidad($cad).'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.stock($RResP["Id"], $cad."-01", $RResA["Id"]).'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResA["Nombre"].'</td>
                </tr>');
            
                $J++;
                if($bgcolor=="#ffffff"){$bgcolor="#cccccc";}
                elseif($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
        }
    }
}

echo $cadena;

function stock($idproducto, $caducidad, $almacen)
{
    include('../conexion.php');

    $ResStock=mysqli_query($conn, "SELECT * FROM productos_inventario WHERE Idproducto='".$idproducto."' AND IdAlmacen='".$almacen."' AND Caducidad='".$caducidad."' ORDER BY Fecha ASC");
    $stock=0;

    while($RResStock=mysqli_fetch_array($ResStock))
    {
        if ($RResStock["Movimiento"]=='I')
        {
            $stock=$RResStock["Cantidad"];
        }
        elseif($RResStock["Movimiento"]=='E')
        {
            $stock=$stock+$RResStock["Cantidad"];
        }
        elseif($RResStock["Movimiento"]=='S')
        {
            $stock=$stock-$RResStock["Cantidad"];
        }
    }

    return $stock;
}
?>
<script>
function repo_caducidad(annomes){
    $.ajax({
				type: 'POST',
				url : 'almacen/repcaducidad.php',
                data: 'annomes=' + annomes
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}
