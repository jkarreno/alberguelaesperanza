<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResCat=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM cat_almacenes WHERE Id='".$_POST["cat"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Nueva sub categoría de '.$ResCat["Nombre"].'</h2>
            <form name="fadsubcatalmacen" id="fadsubcatalmacen">
                <div class="c100">
                    <label class="l_form">Nombre:</label>
                    <input type="text" name="subcatalmacen" id="subcatalmacen">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addsubcatalmacen">
                <input type="hidden" name="cat" id="cat" value="'.$ResCat["Id"].'">
				<input type="submit" name="botadsubcatalmacen" id="botadsubcatalmacen" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadsubcatalmacen").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadsubcatalmacen"));

	$.ajax({
		url: "configuracion/almacenes/sub_cat_almacen.php",
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