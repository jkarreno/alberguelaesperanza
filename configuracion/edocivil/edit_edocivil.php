<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResEC=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM edocivil WHERE Id='".$_POST["edocivil"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar Estado Civil</h2>
            <form name="fededocivil" id="fededocivil">
                <div class="c100">
                    <label class="l_form">Estado Civil:</label>
                    <input type="text" name="edocivil" id="edocivil" value="'.$ResEC["EdoCivil"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editedocivil">
                <input type="hidden" name="idedocivil" id="idedocivil" value="'.$ResEC["Id"].'">
				<input type="submit" name="botededocivil" id="botededocivil" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fededocivil").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fededocivil"));

	$.ajax({
		url: "configuracion/edocivil/edocivil.php",
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