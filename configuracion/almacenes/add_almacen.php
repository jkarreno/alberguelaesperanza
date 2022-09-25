<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nuevo almacén</h2>
            <form name="fadalmacen" id="fadalmacen">
                <div class="c100">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="almacen" id="almacen">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addalmacen">
				<input type="submit" name="botadalmacen" id="botadalmacen" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadalmacen").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadalmacen"));

	$.ajax({
		url: "configuracion/almacenes/almacenes.php",
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