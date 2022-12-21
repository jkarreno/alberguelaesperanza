<?php
date_default_timezone_set('America/Mexico_City');
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

if($_POST["movimiento"]!='S')
{
    $cadena='<input type="month" name="caducidad" id="caducidad">';
}
else
{
    $cadena='<select name="caducidad" id="caducidad">';
    
    $ResCaducidad=mysqli_query($conn, "SELECT Caducidad FROM productos_inventario 
                                        WHERE Movimiento!='S' AND IdProducto = '".$_POST["producto"]."' AND IdAlmacen = '".$_POST["almacen"]."' 
                                        AND Caducidad!='1900-01-01'");
    while($RResCaducidad=mysqli_fetch_array($ResCaducidad))
    {
        $cad=explode('-', $RResCaducidad["Caducidad"]);
        switch ($cad[1])
        {
            case '01': $mes='enero'; break;
            case '02': $mes='febrero'; break;
            case '03': $mes='marzo'; break;
            case '04': $mes='abril'; break;
            case '05': $mes='mayo'; break;
            case '06': $mes='junio'; break;
            case '07': $mes='julio'; break;
            case '08': $mes='agosto'; break;
            case '09': $mes='septiembre'; break;
            case '10': $mes='octubre'; break;
            case '11': $mes='noviembre'; break;
            case '12': $mes='diciembre'; break;
        }

        //busca cantidad con esa fecha de caducidad
        $ResEntro=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS Entrada FROM productos_inventario 
                                        WHERE IdProducto = '".$_POST["producto"]."' AND IdAlmacen = '".$_POST["almacen"]."' 
                                        AND (Movimiento='I' OR Movimiento='E')"));

        //busca salidas con esa fecha de caducidad
        $ResSalio=mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Cantidad) AS Salida FROM productos_inventario 
                                        WHERE IdProducto = '".$_POST["producto"]."' AND IdAlmacen = '".$_POST["almacen"]."' 
                                        AND Movimiento='S'"));

        if($ResSalio["Salida"]<$ResEntro["Entrada"])
        {
            $cadena.='<option value="'.$RResCaducidad["Caducidad"].'">'.$mes.' - '.$cad[0].'</option>';
        }
    
    }

    $cadena.='</select>';
}

echo $cadena;
?>