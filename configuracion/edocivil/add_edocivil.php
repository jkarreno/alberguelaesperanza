<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nuevo estado civil</h2>
            <form name="fadedocivil" id="fadedocivil">
                <div class="c100">
                    <label class="l_form">Nombre estado civil:</label>
                    <input type="text" name="edocivil" id="edocivil">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addedocivil">
				<input type="submit" name="botadedocivil" id="botadedocivil" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadedocivil").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadedocivil"));

	$.ajax({
		url: "configuracion/edocivil/edocivil.php",
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