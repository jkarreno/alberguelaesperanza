<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM pacientes WHERE Id='".$_POST["idpaciente"]."' LIMIT 1"));


$cadena='<div class="c100 card">
            <h2>Reconocimiento para el paciente '.$ResPac["Nombre"].' '.$ResPac["Apellidos"].'</h2>
            <form name="fadrecpac" id="fadrecpac">
                <div class="c100">
                    <label class="l_form">Reconocimiento:</label>
                    <input type="text" name="reconocimiento" id="reconocimiento">
                </div>

                <input type="hidden" name="hacer" id="hacer" value="addrecopac">
                <input type="hidden" name="id" id="id" value="'.$ResPac["Id"].'">
				<input type="submit" name="botaduser" id="botaduser" value="Reconocimiento>>">
			</form>
        </div>';

echo $cadena;
?>
<script>
$("#fadrecpac").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadrecpac"));

	$.ajax({
		url: "pacientes/reco_paciente.php",
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