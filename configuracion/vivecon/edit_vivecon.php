<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResViv=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM vivecon WHERE Id='".$_POST["vivecon"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar Vive Con</h2>
            <form name="fedvivecon" id="fedvivecon">
                <div class="c100">
                    <label class="l_form">Vive Con:</label>
                    <input type="text" name="vivecon" id="vivecon" value="'.$ResViv["ViveCon"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editvivecon">
                <input type="hidden" name="idvivecon" id="idvivecon" value="'.$ResViv["Id"].'">
				<input type="submit" name="botedvivecon" id="botedvivecon" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedvivecon").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedvivecon"));

	$.ajax({
		url: "configuracion/vivecon/vivecon.php",
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