<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResMat=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM material_apoyo WHERE Id='".$_POST["material"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Inventario para '.$ResMat["Nombre"].'</h2>
            <form name="fedmatii" id="fedmatii">
                <div class="c100">
                    <label class="l_form">Cantidad:</label>
                    <input type="number" name="cant_material" id="cant_material">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="ii_material">
                <input type="hidden" name="idmaterial" id="idmaterial" value="'.$ResMat["Id"].'">
				<input type="submit" name="botadinv" id="botadinv" value="Guardar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedmatii").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedmatii"));

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