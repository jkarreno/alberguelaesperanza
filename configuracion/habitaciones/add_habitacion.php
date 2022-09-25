<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nueva habitación</h2>
            <form name="fadhabitacion" id="fadhabitacion">
                <div class="c60">
                    <label class="l_form">Habitación:</label>
                    <input type="text" name="habitacion" id="habitacion">
                </div>
				<div class="c30">
					<label class="l_form">Tipo:</label>
					<select name="tipo" id="tipo">
						<option value="0">Mixta</option>
						<option value="1">Mujeres</option>
						<option value="2">Hombres</option>
					</select>
				</div>
                
                <input type="hidden" name="hacer" id="hacer" value="addhabitacion">
				<input type="submit" name="botadhabitacion" id="botadhabitacion" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadhabitacion").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadhabitacion"));

	$.ajax({
		url: "configuracion/habitaciones/habitaciones.php",
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