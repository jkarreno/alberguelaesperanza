<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nueva Ocupacion</h2>
            <form name="fadocupacion" id="fadocupacion">
                <div class="c100">
                    <label class="l_form">Nombre ocupacion:</label>
                    <input type="text" name="ocupacion" id="ocupacion">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addocupacion">
				<input type="submit" name="botadocupacion" id="botadocupacion" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadocupacion").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadocupacion"));

	$.ajax({
		url: "configuracion/ocupaciones/ocupaciones.php",
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