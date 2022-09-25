<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nuevo estado civil</h2>
            <form name="faddiagnostico" id="faddiagnostico">
                <div class="c100">
                    <label class="l_form">Nombre diagnostico:</label>
                    <input type="text" name="diagnostico" id="diagnostico">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="adddiagnostico">
				<input type="submit" name="botaddiagnostico" id="botaddiagnostico" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#faddiagnostico").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("faddiagnostico"));

	$.ajax({
		url: "configuracion/diagnosticos/diagnosticos.php",
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