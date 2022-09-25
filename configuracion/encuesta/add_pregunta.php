<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nueva Pregunta</h2>
            <form name="fadpregunta" id="fadpregunta">
                <div class="c70">
                    <label class="l_form">Pregunta:</label>
                    <input type="text" name="pregunta" id="pregunta">
                </div>
				<div class="c20">
					<label class="l_form">Tipo:</label>
					<select name="tipo" id="tipo">
						<option value="0">Pregunta abierta</option>
						<option value="1">Opción Multiple</option>
						<option value="2">Multiselección</option>
					</select>
				</div>
                
                <input type="hidden" name="hacer" id="hacer" value="addpregunta">
				<input type="submit" name="botadpregunta" id="botadpregunta" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadpregunta").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadpregunta"));

	$.ajax({
		url: "configuracion/encuesta/encuesta.php",
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