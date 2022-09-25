<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResCAlm=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM cat_almacenes WHERE Id='".$_POST["cat"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar ategoría almacén</h2>
            <form name="fedcatalmacen" id="fedcatalmacen">
                <div class="c100">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="catalmacen" id="catalmacen" value="'.$ResCAlm["Nombre"].'">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="editcatalmacen">
                <input type="hidden" name="idcatalmacen" id="idcatalmacen" value="'.$ResCAlm["Id"].'">
				<input type="submit" name="botedcatalmacen" id="botedcatalmacen" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedcatalmacen").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedcatalmacen"));

	$.ajax({
		url: "configuracion/almacenes/cat_almacenes.php",
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