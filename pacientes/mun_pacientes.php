<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResMun=mysqli_query($conn, "SELECT * FROM municipios WHERE Estado='".$_POST["estado"]."' ORDER BY Municipio ASC");

$cadena='<label class="l_form">Municipio:</label>
            <select name="municipio" id="municipio';
while($RResMun=mysqli_fetch_array($ResMun))
{
    $cadena.='  <option value="'.$RResMun["Id"].'">'.utf8_encode($RResMun["Municipio"]).'</option>';
}
$cadena.='  </datalist>';

echo $cadena;
?>