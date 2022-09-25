<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResLen=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM lenguas WHERE Id='".$_POST["lengua"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar Lengua</h2>
            <form name="fedlengua" id="fedlengua">
                <div class="c100">
                    <label class="l_form">Lengua:</label>
                    <input type="text" name="lengua" id="lengua" value="'.$ResLen["Lengua"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editlengua">
                <input type="hidden" name="idlengua" id="idlengua" value="'.$ResLen["Id"].'">
				<input type="submit" name="botedlengua" id="botedlengua" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedlengua").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedlengua"));

	$.ajax({
		url: "configuracion/lengua/lenguas.php",
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