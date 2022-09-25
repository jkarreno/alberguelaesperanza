<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nueva categoría almacén</h2>
            <form name="fadcatalmacen" id="fadcatalmacen">
                <div class="c100">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="catalmacen" id="catalmacen">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addcatalmacen">
				<input type="submit" name="botadcatalmacen" id="botadcatalmacen" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadcatalmacen").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadcatalmacen"));

	$.ajax({
		url: "configuracion/almacenes/cat_almacenes.php",
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