<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResEsc=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM escolaridad WHERE Id='".$_POST["escolaridad"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar Escolaridad</h2>
            <form name="fedescolaridad" id="fedescolaridad">
                <div class="c100">
                    <label class="l_form">escolaridad:</label>
                    <input type="text" name="escolaridad" id="escolaridad" value="'.$ResEsc["Escolaridad"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editescolaridad">
                <input type="hidden" name="idescolaridad" id="idescolaridad" value="'.$ResEsc["Id"].'">
				<input type="submit" name="botedescolaridad" id="botedescolaridad" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedescolaridad").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedescolaridad"));

	$.ajax({
		url: "configuracion/escolaridad/escolaridad.php",
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