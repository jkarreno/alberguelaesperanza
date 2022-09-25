<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResRes=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM reservacion WHERE Id='".$_POST["idres"]."' LIMIT 1"));
$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$ResRes["IdPaciente"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Cancelar Reservación</h2>
            <label class="l_form">Esta seguro de cancelar la reservación del paciente '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</label>
            <label class="l_form"><a href="#" onclick="reservaciones(\''.$ResRes["Fecha"].'\')">No</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="can_reservacion_2(\''.$ResRes["Id"].'\', \''.$ResRes["Fecha"].'\')">Si</a></label>
        </div>';
    
echo $cadena;

?>
<script>
function can_reservacion_2(idres, fecha){

    $.ajax({
                type: 'POST',
                url : 'reservaciones/reservaciones.php',
                data: 'hacer=canreservacion&idres=' + idres + '&fechares=' + fecha
    }).done (function ( info ){
        $('#contenido').html(info);
    });
}

</script>