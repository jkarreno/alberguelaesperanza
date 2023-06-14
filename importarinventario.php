<?php
include("conexion.php");

$csv = file('Bodega.csv');

foreach ($csv as $linea) {
    $linea = str_getcsv($linea, ",");

    //despliega datos
    echo '<p>
            <strong>Nombre Producto: </strong>'.$linea[0].'<br />
            <strong>Cantidad: </strong>'.$linea[1].'<br />';

    mysqli_query($conn, "INSERT INTO productos (Nombre) VALUES ('".$linea[0]."')");

    $ResId=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM productos ORDER BY Id DESC LIMIT 1"));

    mysqli_query($conn, "INSERT INTO productos_inventario (IdProducto, IdAlmacen, Movimiento, Cantidad, Stock, Fecha, Observaciones)
                                                    VALUES('".$ResId["Id"]."', '3', 'I', '".$linea[1]."', '".$linea[1]."', '".date("Y-m-d")."', 'Carga de inventario por admin')") or die(mysqli_error($conn));

}
?>