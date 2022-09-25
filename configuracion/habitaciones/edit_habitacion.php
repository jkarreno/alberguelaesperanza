<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$ResHab=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM habitaciones WHERE Id='".$_POST["habitacion"]."' LIMIT 1"));

$cadena='<div class="c100 card">
            <h2>Editar habitacion</h2>
            <form name="fedhabitacion" id="fedhabitacion">
                <div class="c60">
                    <label class="l_form">Habitación:</label>
                    <input type="text" name="habitacion" id="habitacion" value="'.$ResHab["Habitacion"].'">
                </div>
				<div class="c30">
					<label class="l_form">Tipo:</label>
					<select name="tipo" id="tipo">
						<option value="0"';if($ResHab["Tipo"]==0){$cadena.=' selected';}$cadena.='>Mixta</option>
						<option value="1"';if($ResHab["Tipo"]==1){$cadena.=' selected';}$cadena.='>Mujeres</option>
						<option value="2"';if($ResHab["Tipo"]==2){$cadena.=' selected';}$cadena.='>Hombres</option>
					</select>
				</div>
                
                <input type="hidden" name="hacer" id="hacer" value="edithabitacion">
                <input type="hidden" name="idhabitacion" id="idhabitacion" value="'.$ResHab["Id"].'">
				<input type="submit" name="botedhabitacion" id="botedhabitacion" value="Editar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fedhabitacion").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fedhabitacion"));

	$.ajax({
		url: "configuracion/habitaciones/habitaciones.php",
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