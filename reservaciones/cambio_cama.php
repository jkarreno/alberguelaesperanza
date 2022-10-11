<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');


//buscar paciente de reservación
$ResPaciente=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2 FROM pacientes WHERE Id=(SELECT IdPA FROM reservaciones WHERE IdReservacion='".$_POST["idreservacion"]."' AND Tipo='P' GROUP BY IdPA)"));
//buscar acompañantes de reservación
//$ResAcompannantes=mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2 FROM acompannantes WHERE Id=(SELECT IdPA FROM reservaciones WHERE IdReservacion='".$_POST["idreservacion"]."' AND Tipo='A' GROUP BY IdPA)");
//ultima fecha de reservacion
$ResFechaF=mysqli_fetch_array(mysqli_query($conn, "SELECT Fecha FROM reservaciones WHERE IdReservacion='".$_POST["idreservacion"]."' ORDER BY Fecha DESC LIMIT 1"));

$cadena.='<div class="c100 card" style="border:0; box-shadow: none;">
            <h2>Cambio de camas reservación '.$_POST["idreservacion"].'</h2>
            <form name="fcambio_cama" id="fcambio_cama">
            <div class="c100">
                <label class="l_form">Paciente: </label>
            </div>
            <div class="c45">
                <label class="l_form">'.$ResPaciente["Nombre"].' '.$ResPaciente["Apellidos"].' '.$ResPaciente["Apellidos2"].'</label>
                <input type="hidden" name="paciente" id="paciente" value="'.$ResPaciente["Id"].'">
            </div>
            <div class="c45">
                <select name="cama_p_'.$ResPaciente["Id"].'" id="cama_p_'.$ResPaciente["Id"].'" onmousedown="if(this.options.length>5){this.size=5; this.style.height=105;}" onchange="this.size=0; this.style.height=35;" onblur="this.size=0; this.style.height=35;" required>
                    <option>Seleccione</option>
                    <option value="0">No requiere cama</option>';

$ResCamas=mysqli_query($conn, "SELECT c.Id, h.Habitacion, c.Cama, h.Id AS hid FROM camas AS c 
                    INNER JOIN habitaciones as h ON c.Habitacion = h.Id 
                    ORDER BY h.Habitacion ASC, c.Cama ASC");

$disp=0; $hab=1;
while($RResCam=mysqli_fetch_array($ResCamas))
{
    //busca si la cama esta disponible
    //$ResCamaD=mysqli_query($conn, "SELECT Id FROM reservaciones WHERE IdReservacion!='".$_POST["idreservacion"]."' 
    //                                AND Cama='".$RResCam["Id"]."' AND Fecha>='".$_POST["fechares"]."' AND Fecha<='".$ResFechaF["Fecha"]."'");

    //if(mysqli_num_rows($ResCamaD)==0)
    //{
        //busca la cama del paciente
        $ResCamaP=mysqli_query($conn, "SELECT Id FROM reservaciones 
        WHERE IdReservacion='".$_POST["idreservacion"]."' AND Cama='".$RResCam["Id"]."' AND Tipo='P' 
                AND IdPA='".$ResPaciente["Id"]."' AND Fecha>='".$_POST["fechares"]."'");
        $cadena.='   <option value="'.$RResCam["Id"].'"';if(mysqli_num_rows($ResCamaP)>0){$cadena.=' selected';}$cadena.='>'.$RResCam["Habitacion"].' - '.$RResCam["Cama"].'</option>';
    //}
}
$cadena.='      </select>
            </div>


            <div class="c100">
                <label class="l_form">Acompañantes: </label>
            </div>';
$ResAcompanantes=mysqli_query($conn, "SELECT IdPA FROM reservaciones WHERE IdReservacion='".$_POST["idreservacion"]."' AND Tipo='A' GROUP BY IdPA");
while($RResAcom=mysqli_fetch_array($ResAcompanantes))
{
    $ResA=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2 FROM acompannantes WHERE Id='".$RResAcom["IdPA"]."'"));

    $cadena.='<div class="c45">
                <label class="l_form">'.$ResA["Nombre"].' '.$ResA["Apellidos"].' '.$ResA["Apellidos2"].'</label>
                <input type="hidden" name="paciente" id="paciente" value="'.$ResPaciente["Id"].'">
            </div>
            <div class="c45">
                <select name="cama_a_'.$RResAcom["IdPA"].'" id="cama_a_'.$RResAcom["IdPA"].'" onmousedown="if(this.options.length>5){this.size=5; this.style.height=105;}" onchange="this.size=0; this.style.height=35;" onblur="this.size=0; this.style.height=35;" required>
                    <option>Seleccione</option>
                    <option value="0">No requiere cama</option>';
    $ResCamasA=mysqli_query($conn, "SELECT c.Id, h.Habitacion, c.Cama, h.Id AS hid FROM camas AS c 
                                    INNER JOIN habitaciones as h ON c.Habitacion = h.Id 
                                    ORDER BY h.Habitacion ASC, c.Cama ASC");
    while($RResCamasA=mysqli_fetch_array($ResCamasA))
    {
        $ResCamaA=mysqli_query($conn, "SELECT Id FROM reservaciones 
        WHERE IdReservacion='".$_POST["idreservacion"]."' AND Cama='".$RResCamasA["Id"]."' AND Tipo='A' 
                AND IdPA='".$RResAcom["IdPA"]."' AND Fecha>='".$_POST["fechares"]."'");
        $cadena.='  <option value="'.$RResCamasA["Id"].'"';if(mysqli_num_rows($ResCamaA)>0){$cadena.=' selected';}$cadena.='>'.$RResCamasA["Habitacion"].' - '.$RResCamasA["Cama"].'</option>';
    }
    $cadena.='  </select>
            </div>';
}
$cadena.='<div class="c100">
            <input type="hidden" name="idreservacion" id="idreservacion" value="'.$_POST["idreservacion"].'">
            <input type="hidden" name="hacer" id="hacer" value="cambiar_cama">
            <input type="hidden" name="fechares" id="fechares" value="'.$_POST["fechares"].'">
            <input type="submit" name="botadreserv" id="botadreserv" value="Reservar>>" onclick="cerrarmodal()">
        </div>
        </div>';

echo $cadena;

?>
<script>
$("#fcambio_cama").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fcambio_cama"));

	$.ajax({
		url: "reservaciones/reservaciones.php",
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