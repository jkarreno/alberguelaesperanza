<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$_POST["idpaciente"]."' LIMIT 1"));


$cadena='<div class="c100 card">
            <h2>Amonestacion para el paciente '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</h2>
            <form name="fadamopac" id="fadamopac">
                <div class="c100">
                    <label class="l_form">Amonestación:</label>
                    <input type="text" name="amonestacion" id="amonestacion">
                </div>

                <input type="hidden" name="hacer" id="hacer" value="addamopac">
                <input type="hidden" name="id" id="id" value="'.$ResPac["Id"].'">
				<input type="submit" name="botaduser" id="botaduser" value="Amonestar>>">
			</form>
        </div>';

echo $cadena;
?>
<script>
$("#fadamopac").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadamopac"));

	$.ajax({
		url: "pacientes/amo_pacientes.php",
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
</script>