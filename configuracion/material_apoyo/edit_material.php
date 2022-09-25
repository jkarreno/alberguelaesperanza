<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResMat=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM material_apoyo WHERE Id='".$_POST["material"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar Material de Apoyo</h2>
            <form name="fedmaterial" id="fedmaterial">
                <div class="c100">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="material" id="material" value="'.$ResMat["Nombre"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editmaterial">
                <input type="hidden" name="idmaterial" id="idmaterial" value="'.$ResMat["Id"].'">
				<input type="submit" name="botedmaterial" id="botedmaterial" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedmaterial").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedmaterial"));

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