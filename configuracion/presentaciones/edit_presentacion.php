<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResUni=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM presentaciones WHERE Id='".$_POST["presentacion"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar presentacion</h2>
            <form name="fedpresentacion" id="fedpresentacion">
                <div class="c100">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="presentacion" id="presentacion" value="'.$ResUni["Nombre"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editpresentacion">
                <input type="hidden" name="idpresentacion" id="idpresentacion" value="'.$ResUni["Id"].'">
				<input type="submit" name="botedpresentacion" id="botedpresentacion" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedpresentacion").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedpresentacion"));

	$.ajax({
		url: "configuracion/presentaciones/presentaciones.php",
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