<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResDi=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM diagnosticos WHERE Id='".$_POST["diagnostico"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar Diagnostico</h2>
            <form name="feddiagnostico" id="feddiagnostico">
                <div class="c100">
                    <label class="l_form">Diagnostico:</label>
                    <input type="text" name="diagnostico" id="diagnostico" value="'.utf8_encode($ResDi["Diagnostico"]).'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editdiagnostico">
                <input type="hidden" name="iddiagnostico" id="iddiagnostico" value="'.$ResEC["Id"].'">
				<input type="submit" name="boteddiagnostico" id="boteddiagnostico" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#feddiagnostico").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("feddiagnostico"));

	$.ajax({
		url: "configuracion/diagnosticos/diagnosticos.php",
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