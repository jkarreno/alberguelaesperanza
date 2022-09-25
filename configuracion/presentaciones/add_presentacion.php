<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nueva presentacion</h2>
            <form name="fadpresentacion" id="fadpresentacion">
                <div class="c100">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="presentacion" id="presentacion">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addpresentacion">
				<input type="submit" name="botadpresentacion" id="botadpresentacion" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadpresentacion").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadpresentacion"));

	$.ajax({
		url: "configuracion/presentaciones/presentaciones.php",
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