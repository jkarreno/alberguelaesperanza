<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nueva escolaridad</h2>
            <form name="fadescolaridad" id="fadescolaridad">
                <div class="c100">
                    <label class="l_form">Escolaridad:</label>
                    <input type="text" name="escolaridad" id="escolaridad">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addescolaridad">
				<input type="submit" name="botadescolaridad" id="botadescolaridad" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadescolaridad").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadescolaridad"));

	$.ajax({
		url: "configuracion/escolaridad/escolaridad.php",
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