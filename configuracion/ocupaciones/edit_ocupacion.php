<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResOcu=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM ocupaciones WHERE Id='".$_POST["ocupacion"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar ocupacion</h2>
            <form name="fedocupacion" id="fedocupacion">
                <div class="c100">
                    <label class="l_form">Nombre ocupacion:</label>
                    <input type="text" name="ocupacion" id="ocupacion" value="'.$ResOcu["Ocupacion"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editocupacion">
                <input type="hidden" name="idocupacion" id="idocupacion" value="'.$ResOcu["Id"].'">
				<input type="submit" name="botedocupacion" id="botedocupacion" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedocupacion").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedocupacion"));

	$.ajax({
		url: "configuracion/ocupaciones/ocupaciones.php",
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