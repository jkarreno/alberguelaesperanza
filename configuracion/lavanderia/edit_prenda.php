<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResPren=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM lavanderia WHERE Id='".$_POST["prenda"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar Prenda</h2>
            <form name="fedprenda" id="fedprenda">
                <div class="c100">
                    <label class="l_form">Nombre prenda:</label>
                    <input type="text" name="prenda" id="prenda" value="'.$ResPren["Prenda"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editprenda">
                <input type="hidden" name="idprenda" id="idprenda" value="'.$ResPren["Id"].'">
				<input type="submit" name="botedprenda" id="botedprenda" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedprenda").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedprenda"));

	$.ajax({
		url: "configuracion/lavanderia/lavanderia.php",
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