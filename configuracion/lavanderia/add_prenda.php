<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nueva Prenda</h2>
            <form name="fadprenda" id="fadprenda">
                <div class="c100">
                    <label class="l_form">Nombre Prenda:</label>
                    <input type="text" name="prenda" id="prenda">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addprenda">
				<input type="submit" name="botadprenda" id="botadprenda" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadprenda").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadprenda"));

	$.ajax({
		url: "configuracion/lavanderia/lavanderia.php",
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