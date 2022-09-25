<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResP=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM preguntas_encuesta WHERE Id='".$_POST["pregunta"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Nueva Pregunta</h2>
            <form name="feditpregunta" id="feditpregunta">
                <div class="c70">
                    <label class="l_form">Pregunta:</label>
                    <input type="text" name="pregunta" id="pregunta" value="'.$ResP["Pregunta"].'">
                </div>
				<div class="c20">
					<label class="l_form">Tipo:</label>
					<select name="tipo" id="tipo">
						<option value="0"';if($ResP["Tipo"]=='0'){$cadena.=' selected';}$cadena.='>Pregunta abierta</option>
						<option value="1"';if($ResP["Tipo"]=='1'){$cadena.=' selected';}$cadena.='>Opción Multiple</option>
						<option value="2"';if($ResP["Tipo"]=='2'){$cadena.=' selected';}$cadena.='>Multiselección</option>
					</select>
				</div>
                
                <input type="hidden" name="hacer" id="hacer" value="editpregunta">
                <input type="hidden" name="idpregunta" id="idpregunta" value="'.$ResP["Id"].'">
				<input type="submit" name="boteditpregunta" id="boteditpregunta" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#feditpregunta").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("feditpregunta"));

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