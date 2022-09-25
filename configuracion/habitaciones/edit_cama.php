<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResCama=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM camas WHERE Id='".$_POST["cama"]."'"));

$ResHab=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM habitaciones WHERE Id='".$ResCama["Habitacion"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Nueva cama de la habitación '.$ResHab["Habitacion"].'</h2>
            <form name="fadcama" id="fadcama">
                <div class="c100">
                    <label class="l_form">Cama:</label>
                    <input type="text" name="cama" id="cama" value="'.$ResCama["Cama"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editcama">
                <input type="hidden" name="habitacion" id="habitacion" value="'.$ResHab["Id"].'">
                <input type="hidden" name="idcama" id="idcama" value="'.$ResCama["Id"].'">
				<input type="submit" name="botadcama" id="botadcama" value="Actualizar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadcama").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadcama"));

	$.ajax({
		url: "configuracion/habitaciones/camas_habitacion.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#contenido2").html(echo);
	});
});
</script>