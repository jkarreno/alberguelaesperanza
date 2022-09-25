<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResIns=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM institutos WHERE Id='".$_POST["instituto"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar instituto</h2>
            <form name="fedinstituto" id="fedinstituto">
                <div class="c100">
                    <label class="l_form">Nombre Instituto:</label>
                    <input type="text" name="instituto" id="instituto" value="'.$ResIns["Instituto"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editinstituto">
                <input type="hidden" name="idinstituto" id="idinstituto" value="'.$ResIns["Id"].'">
				<input type="submit" name="botedinstituto" id="botedinstituto" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedinstituto").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedinstituto"));

	$.ajax({
		url: "configuracion/institutos/institutos.php",
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