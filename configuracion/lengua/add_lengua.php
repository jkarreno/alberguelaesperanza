<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nueva Lengua</h2>
            <form name="fadlengua" id="fadlengua">
                <div class="c100">
                    <label class="l_form">Lengua:</label>
                    <input type="text" name="lengua" id="lengua">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addlengua">
				<input type="submit" name="botadlengua" id="botadlengua" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadlengua").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadlengua"));

	$.ajax({
		url: "configuracion/lengua/lenguas.php",
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