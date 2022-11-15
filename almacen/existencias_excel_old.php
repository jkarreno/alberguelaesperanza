<?php 
//Inicio la sesion 
session_start();
include('../conexion.php');
include('../funciones.php');
include ("../caja/excelgen.class.php");

//initiate a instance of "excelgen" class
$excel = new ExcelGen("ExistenciasAlmacen");

//initiate $row,$col variables
$row=0;
$col=0;

$excel->WriteText($row,$col,"Producto");$col++;
$excel->WriteText($row,$col,"Presentacion");$col++;
$excel->WriteText($row,$col,"Almacen");$col++;
$excel->WriteText($row,$col,"Existencia");$col++;

$row++;
$col=0;

$ResProductos=mysqli_query($conn, "SELECT * FROM productos ORDER BY Nombre ASC");
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
                $excel->WriteText($row,$col,strtoupper(utf8_encode($RResP["Nombre"])).' - '.$RResP["Volumen"]);$col++;
                $excel->WriteText($row,$col,$ResPr["Nombre"]);$col++;
                $excel->WriteText($row,$col,$RResA["Nombre"]);$col++;
                $excel->WriteText($row,$col,$RResI["Stock"]);$col++;

                $row++;
                $col=0;
            }
        }
    }

    
}

//stream Excel for user to download or show on browser
$excel->SendFile();