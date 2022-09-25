<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nuevo Vive Cn</h2>
            <form name="fadvivecon" id="fadvivecon">
                <div class="c100">
                    <label class="l_form">Vive Con:</label>
                    <input type="text" name="vivecon" id="vivecon">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addvivecon">
				<input type="submit" name="botadvivecon" id="botadvivecon" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadvivecon").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadvivecon"));

	$.ajax({
		url: "configuracion/vivecon/vivecon.php",
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