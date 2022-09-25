<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM preguntas_encuesta WHERE Id='".$_POST["pregunta"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Nueva Respuesta para la pregunta '.$ResP["Pregunta"].'</h2>
            <form name="fadrespuesta" id="fadrespuesta">
                <div class="c100">
                    <label class="l_form">Respuesta:</label>
                    <input type="text" name="respuesta" id="respuesta">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addrespuesta">
                <input type="hidden" name="pregunta" id="pregunta" value="'.$_POST["pregunta"].'">
				<input type="submit" name="botadpregunta" id="botadpregunta" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadrespuesta").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadrespuesta"));

	$.ajax({
		url: "configuracion/encuesta/respuestas.php",
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