<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResAlm=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM almacenes WHERE Id='".$_POST["almacen"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar almacén</h2>
            <form name="fedalmacen" id="fedalmacen">
                <div class="c100">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="almacen" id="almacen" value="'.$ResAlm["Nombre"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editalmacen">
                <input type="hidden" name="idalmacen" id="idalmacen" value="'.$ResAlm["Id"].'">
				<input type="submit" name="botedalmacen" id="botedalmacen" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedalmacen").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedalmacen"));

	$.ajax({
		url: "configuracion/almacenes/almacenes.php",
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