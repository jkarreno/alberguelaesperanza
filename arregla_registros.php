<?php

include ('conexion.php');

//leer los pacientes
//$ResPacientes=mysqli_query($conn, "SELECT Id, Diagnostico1 FROM pacientes WHERE Diagnostico1!='' ");
//
//while($RResPac=mysqli_fetch_array($ResPacientes))
//{
//    //buscar en diagnosticos
//    $RDiag=mysqli_query($conn, "SELECT * FROM diagnosticos WHERE Diagnostico='".$RResPac["Diagnostico1"]."'");
//
//    if(mysqli_num_rows($RDiag)==0)
//    {
//        mysqli_query($conn, "INSERT INTO diagnosticos (Diagnostico) VALUES ('".$RResPac["Diagnostico1"]."')");
//
//        $RDiag=mysqli_query($conn, "SELECT * FROM diagnosticos WHERE Diagnostico='".$RResPac["Diagnostico1"]."'");
//    }
//    $ResDiag=mysqli_fetch_array($RDiag);
//
//    //actualiza pacientes
//    mysqli_query($conn, "UPDATE pacientes SET Diagnostico1='".$ResDiag["Id"]."' WHERE Id='".$RResPac["Id"]."'");
//
//    echo $RResPac["Id"].' - '.$RResPac["Diagnostico1"].' > '.$ResDiag["Id"].'<br />';
//}

$ResRes=mysqli_query($conn, "SELECT * FROM `reservacion` WHERE `Diagnostico` = 218");
while($RResRes=mysqli_fetch_array($ResRes))
{
    $ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Instituto1, Diagnostico1 FROM pacientes WHERE Id='".$RResRes["IdPaciente"]."'"));

    mysqli_query($conn, "UPDATE reservacion SET Diagnostico='".$ResPac["Diagnostico1"]."' WHERE Id='".$RResRes["Id"]."'");

    echo "Actualizada reservación ".$RResRes["Id"].'<br />';
}