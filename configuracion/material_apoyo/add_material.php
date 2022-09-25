<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nuevo material de apoyo</h2>
            <form name="fadmaterial" id="fadmaterial">
                <div class="c100">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="material" id="material">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addmaterial">
				<input type="submit" name="botadmaterial" id="botadmaterial" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadmaterial").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadmaterial"));

	$.ajax({
		url: "configuracion/material_apoyo/material_apoyo.php",
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