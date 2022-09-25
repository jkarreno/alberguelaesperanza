<?php
//Inicio la sesion 
session_start();

include('../conexion.php');

$ResAco=mysqli_fetch_array(mysqli_query($conn, "SELECT Id, Nombre FROM acompannantes WHERE Id='".$_POST["idacompannante"]."' LIMIT 1"));


$cadena='<div class="c100 card">
            <h2>Amonestacion para '.$ResAco["Nombre"].'</h2>
            <form name="fadamoaco" id="fadamoaco">
                <div class="c100">
                    <label class="l_form">Amonestación:</label>
                    <input type="text" name="amonestacion" id="amonestacion">
                </div>

                <input type="hidden" name="hacer" id="hacer" value="addamoaco">
                <input type="hidden" name="id" id="id" value="'.$ResAco["Id"].'">
				<input type="submit" name="botaduser" id="botaduser" value="Amonestar>>">
			</form>
        </div>';

echo $cadena;
?>
<script>
$("#fadamoaco").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadamoaco"));

	$.ajax({
		url: "pacientes/amo_acompannante.php",
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