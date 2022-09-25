<?php

include('../funciones.php');

$fecha_nac = new DateTime(date('Y/m/d',strtotime($_POST["fecha"]))); // Creo un objeto DateTime de la fecha ingresada
$fecha_hoy = new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
$cedad = date_diff($fecha_hoy,$fecha_nac);
$edadaco=$cedad->format('%Y').' años '.$cedad->format('%m').' meses';

$cadena='<label class="l_form">Edad: </label>
<input type="text" name="edad" id="edad" value="'.$edadaco.'" disabled>';

echo $cadena;