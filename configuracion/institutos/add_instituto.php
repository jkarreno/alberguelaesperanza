<?php
//Inicio la sesion 
session_start();

include('../../conexion.php');

$cadena='<div class="c100 card">
            <h2>Nuevo instituto</h2>
            <form name="fadinstituto" id="fadinstituto">
                <div class="c100">
                    <label class="l_form">Nombre Instituto:</label>
                    <input type="text" name="instituto" id="instituto">
                </div>
                
                <input type="hidden" name="hacer" id="hacer" value="addinstituto">
				<input type="submit" name="botadinstituto" id="botadinstituto" value="Agregar>>">
			</form>
        </div>';
    
echo $cadena;

?>
<script>
$("#fadinstituto").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fadinstituto"));

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