<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualiza Datos</title>
</head>
<body>
<?php
$conn=mysqli_connect ("localhost", "kraps_laesperanza", "Esperanza2021#") or die('Cannot connect to the database because: ' . mysqli_error());
mysqli_select_db ($conn, "kraps_laesperanza");

$ResPacientes=mysqli_query($conn, "SELECT * FROM pacientes WHERE Apellidos2 IS NULL");

while($RResPac=mysqli_fetch_array($ResPacientes))
{
    $ap=explode(' ', $RResPac["Apellidos"]);
    $cadena.=$RResPac["Id"].' - '.$ap[0].' - '.$ap[1].'<br />';
    mysqli_query($conn, "UPDATE pacientes SET Apellidos='".$ap[0]."', Apellidos2='".$ap[1]."' WHERE Id='".$RResPac["Id"]."'");
}

echo $cadena;
?>
    
</body>
</html>

