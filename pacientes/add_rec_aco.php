<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResAco=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre, Apellidos FROM acompannantes WHERE Id='".$_POST["idacompannante"]."' LIMIT 1"));


$cadena='<div class="c100 card">
            <h2>Reconocimiento para '.$ResAco["Nombre"].' '.$ResAco["Apellidos"].'</h2>
            <form name="fadrecaco" id="fadrecaco">
                <div class="c100">
                    <label class="l_form">Reconocimiento:</label>
                    <input type="text" name="reconocimiento" id="reconocimiento">
                </div>

                <input type="hidden" name="hacer" id="hacer" value="addrecaco">
                <input type="hidden" name="id" id="id" value="'.$ResAco["Id"].'">
				<input type="submit" name="botaduser" id="botaduser" value="Reconocimientor>>">
			</form>
        </div>';

echo $cadena;
?>
<script>
$("#fadrecaco").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadrecaco"));

	$.ajax({
		url: "pacientes/rec_acompannante.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#contenido").html(echo);
	});
});
</script>