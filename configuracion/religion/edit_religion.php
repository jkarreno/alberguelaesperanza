<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResRel=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM religion WHERE Id='".$_POST["religion"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar religion</h2>
            <form name="fedreligion" id="fedreligion">
                <div class="c100">
                    <label class="l_form">Nombre religion:</label>
                    <input type="text" name="religion" id="religion" value="'.$ResRel["Religion"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editreligion">
                <input type="hidden" name="idreligion" id="idreligion" value="'.$ResRel["Id"].'">
				<input type="submit" name="botedreligion" id="botedreligion" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedreligion").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedreligion"));

	$.ajax({
		url: "configuracion/religion/religion.php",
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